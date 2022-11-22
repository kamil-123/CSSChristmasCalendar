<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="Účet s tímto emailem již existuje.")
 */
class User implements UserInterface, AccountInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, name="first_name", nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, name="last_name", nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="boolean", name="is_news", nullable=true)
     */
    private $isNews;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $frequency;

    /**
     * @ORM\Column(type="boolean", name="is_gdpr", nullable=true)
     */
    private $isGdpr;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="created_at")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="updated_at")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastSendAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $changePassword;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tokenCreatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->changePassword = false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = strtolower($email);

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName = null): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName = null): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city = null): self
    {
        $this->city = $city;

        return $this;
    }

    public function getIsNews(): ?bool
    {
        return $this->isNews;
    }

    public function setIsNews(?bool $isNews = null): self
    {
        $this->isNews = $isNews;

        return $this;
    }

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(?string $frequency = null): self
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getIsGdpr(): ?bool
    {
        return $this->isGdpr;
    }

    public function setIsGdpr(?bool $isGdpr = null): self
    {
        $this->isGdpr = $isGdpr;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLastSendAt(): ?\DateTimeInterface
    {
        return $this->lastSendAt;
    }

    public function setLastSendAt(?\DateTimeInterface $lastSendAt): self
    {
        $this->lastSendAt = $lastSendAt;

        return $this;
    }

    public function isChangePassword(): bool
    {
        return $this->changePassword;
    }

    public function setChangePassword(bool $changePassword): void
    {
        $this->changePassword = $changePassword;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getTokenCreatedAt(): ?\DateTimeInterface
    {
        return $this->tokenCreatedAt;
    }

    public function setTokenCreatedAt(?\DateTimeInterface $tokenCreatedAt): self
    {
        $this->tokenCreatedAt = $tokenCreatedAt;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
        $this->tokenCreatedAt = Datetime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s"));
    }

    public function isTokenValid(): bool
    {
        if ($this->token === null || $this->tokenCreatedAt === null ) {
            return false;
        }
        return true;
    }

    public function resetToken(): void
    {
        $this->token = null;
        $this->tokenCreatedAt = null;
    }
}
