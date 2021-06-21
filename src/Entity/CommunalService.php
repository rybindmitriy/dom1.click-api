<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="communal_services")
 */
class CommunalService extends AbstractEntity
{
    /**
     * @ORM\OneToMany(targetEntity=BuildingCommunalService::class, mappedBy="communalService")
     *
     * @psalm-var Collection<int, BuildingCommunalService>
     */
    private Collection $buildingCommunalServices;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $title;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(Uuid $id, string $title)
    {
        Assert::notEmpty($title, 'Не указано название коммунальной услуги');

        parent::__construct($id);

        $this->buildingCommunalServices = new ArrayCollection();
        $this->title                    = $title;
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

    public function getTitle(): string
    {
        return $this->title;
    }
}
