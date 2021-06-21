<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="buildings")
 */
class Building extends AbstractEntity implements UserInterface
{
    /**
     * @ORM\Column(type="string")
     */
    private string $address;

    /**
     * @ORM\Column(type="integer")
     */
    private int $balance;

    /**
     * @ORM\OneToMany(targetEntity=BuildingCommunalService::class, mappedBy="building")
     *
     * @psalm-var Collection<int, BuildingCommunalService>
     */
    private Collection $buildingCommunalServices;

    /**
     * @ORM\OneToMany(targetEntity=CExchange::class, mappedBy="building")
     *
     * @psalm-var Collection<int, CExchange>
     */
    private Collection $cExchanges;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $fiasId;

    /**
     * @ORM\ManyToOne(targetEntity=Organization::class, inversedBy="buildings")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private Organization $organization;

    /**
     * @ORM\Column(type="string", length=12, unique=true)
     */
    private string $registrationCode;

    /**
     * @ORM\OneToMany(targetEntity=Room::class, mappedBy="building")
     *
     * @psalm-var Collection<int, Room>
     */
    private Collection $rooms;

    /**
     * @ORM\Column(type="integer")
     */
    private int $status;

    /**
     * @ORM\Column(type="string")
     */
    private string $timeOffset;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $address,
        string $fiasId,
        Uuid $id,
        Organization $organization,
        string $timeOffset,
    ) {
        Assert::notEmpty($address, 'Не указан адрес здания');
        Assert::notEmpty($fiasId, 'Не указан ФИАС код здания');
        Assert::notEmpty($timeOffset, 'Не указан часовой пояс');

        parent::__construct($id);

        /** @var string $registrationCode */
        $registrationCode = preg_replace('~^(\d{4})(\d{4})(\d{2})~', '$1-$2-$3', (string) time());

        /** @var int $statusValue */
        $statusValue = BuildingStatusEnum::active()->value;

        $this->address                  = $address;
        $this->balance                  = 0;
        $this->buildingCommunalServices = new ArrayCollection();
        $this->cExchanges               = new ArrayCollection();
        $this->fiasId                   = $fiasId;
        $this->organization             = $organization;
        $this->registrationCode         = $registrationCode;
        $this->rooms                    = new ArrayCollection();
        $this->status                   = $statusValue;
        $this->timeOffset               = $timeOffset;
    }

    public function eraseCredentials(): void
    {
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @psalm-return Collection<int, BuildingCommunalService>
     *
     * @return Collection|BuildingCommunalService[]
     */
    public function getBuildingCommunalServices(): Collection
    {
        return $this->buildingCommunalServices;
    }

    /**
     * @psalm-return Collection<int, CExchange>
     *
     * @return Collection|CExchange[]
     */
    public function getCExchanges(): Collection
    {
        return $this->cExchanges;
    }

    public function getFiasId(): string
    {
        return $this->fiasId;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    public function getPassword(): ?string
    {
        return null;
    }

    public function getRegistrationCode(): string
    {
        return $this->registrationCode;
    }

    public function getRoles(): array
    {
        return [];
    }

    /**
     * @psalm-return Collection<int, Room>
     *
     * @return Collection|Room[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getStatus(): BuildingStatusEnum
    {
        return BuildingStatusEnum::from($this->status);
    }

    public function getTimeOffset(): string
    {
        return $this->timeOffset;
    }

    public function getUserIdentifier(): string
    {
        return $this->fiasId;
    }

    /**
     * @deprecated
     */
    public function getUsername(): string
    {
        return $this->fiasId;
    }

    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }

    public function setOrganization(Organization $organization): void
    {
        $this->organization = $organization;
    }

    public function setStatus(BuildingStatusEnum $status): void
    {
        /** @var int $statusValue */
        $statusValue = $status->value;

        $this->status = $statusValue;
    }
}
