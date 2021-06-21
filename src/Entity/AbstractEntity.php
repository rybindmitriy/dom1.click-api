<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

abstract class AbstractEntity
{
    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    protected DateTimeImmutable $createdAt;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     */
    protected Uuid $id;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    protected DateTimeImmutable $updatedAt;

    public function __construct(Uuid $id)
    {
        $this->createdAt = new DateTimeImmutable();
        $this->id        = $id;
        $this->updatedAt = $this->createdAt;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
