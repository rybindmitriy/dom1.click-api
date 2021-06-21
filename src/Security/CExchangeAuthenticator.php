<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Building;
use App\Entity\BuildingStatusEnum;
use App\Entity\OrganizationStatusEnum;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

final class CExchangeAuthenticator extends AbstractAuthenticator
{
    public function authenticate(Request $request): PassportInterface
    {
        $fiasId = $request->query->get('fiasId');

        if (null === $fiasId) {
            throw new CustomUserMessageAuthenticationException('Не указан код ФИАС здания');
        }

        preg_match(
            '~Bearer (?P<cExchangeToken>\S*)~',
            $request->headers->get('Authorization') ?? '',
            $matches
        );

        return
            new Passport(
                new UserBadge($fiasId),
                new CustomCredentials(
                    static function (string $cExchangeToken, Building $building) {
                        $organization = $building->getOrganization();

                        switch ($building->getStatus()) {
                            case BuildingStatusEnum::inactive():
                                throw new CustomUserMessageAuthenticationException('Здание неактивно');
                            case BuildingStatusEnum::paymentRequired():
                                throw new CustomUserMessageAuthenticationException('Требуется оплата');
                            case OrganizationStatusEnum::inactive():
                                throw new CustomUserMessageAuthenticationException('Организация неактивна');
                            default:
                                break;
                        }

                        return $cExchangeToken === $organization->getCExchangeToken();
                    },
                    $matches['cExchangeToken'] ?? ''
                )
            );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        $response = new JsonResponse();

        $response
            ->setData(
                [
                    'error' => $exception->getMessage(),
                ]
            )
            ->setStatusCode(Response::HTTP_UNAUTHORIZED);

        return $response;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function supports(Request $request): bool
    {
        return true;
    }
}
