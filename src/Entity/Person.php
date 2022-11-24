<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $giftName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $christmasDay = false;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $active = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGiftName(): ?string
    {
        return $this->giftName;
    }

    public function setGiftName(string $giftName): self
    {
        $this->giftName = $giftName;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function isChristmasDay(): bool
    {
        return $this->christmasDay;
    }

    public function setChristmasDay(bool $christmasDay): self
    {
        $this->christmasDay = $christmasDay;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getImagePath(): string
    {
        return 'uploads/' . UploaderHelper::GIFT_IMAGE . '/' . $this->getImage();
    }
}
