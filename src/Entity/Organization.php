<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="organizations")
 */
class Organization extends AbstractEntity
{
    /**
     * @ORM\Column(type="integer")
     */
    private int $balance;

    /**
     * @ORM\OneToMany(targetEntity=Building::class, mappedBy="organization")
     *
     * @psalm-var Collection<int, Building>
     */
    private Collection $buildings;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $cExchangeToken;

    /**
     * @ORM\Column(type="string", length=10, unique=true)
     */
    private string $inn;

    /**
     * @ORM\Column(type="smallint")
     */
    private int $status;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private string $title;

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function __construct(Uuid $id, string $inn, string $title)
    {
        Assert::notEmpty($inn, 'Не указан ИНН организации');
        Assert::regex($inn, '~^\d{10}$~', 'ИНН указан неверно');
        Assert::notEmpty($title, 'Не указано название организации');

        parent::__construct($id);

        /** @var int $organizationStatus */
        $organizationStatus = OrganizationStatusEnum::active()->value;

        $this->balance        = 0;
        $this->buildings      = new ArrayCollection();
        $this->cExchangeToken = $this->generateCExchangeToken();
        $this->inn            = $inn;
        $this->status         = $organizationStatus;
        $this->title          = $title;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @psalm-return Collection<int, Building>
     *
     * @return Collection|Building[]
     */
    public function getBuildings(): Collection
    {
        return $this->buildings;
    }

    public function getCExchangeToken(): string
    {
        return $this->cExchangeToken;
    }

    public function getINN(): string
    {
        return $this->inn;
    }

    public function getStatus(): OrganizationStatusEnum
    {
        return OrganizationStatusEnum::from($this->status);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }

    public function setStatus(OrganizationStatusEnum $status): void
    {
        /** @var int $statusValue */
        $statusValue = $status->value;

        $this->status = $statusValue;
    }

    /**
     * @throws Exception
     */
    private function generateCExchangeToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
