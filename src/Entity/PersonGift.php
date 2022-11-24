<?php

namespace App\Entity;

use App\Repository\PersonGiftRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonGiftRepository::class)
 */
class PersonGift
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $day;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $gift;

    public function __construct(int $day, Person $person, Person $gift)
    {
        $this->day = $day;
        $this->person = $person;
        $this->gift = $gift;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function getGift(): ?Person
    {
        return $this->gift;
    }
}
