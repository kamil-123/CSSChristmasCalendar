<?php

namespace App\Entity;

//use App\Entity\Enum\AccountType;


interface AccountInterface
{
    public function getId(): int;

    public function isActive(): ?bool;

//    public function getAccountType(): AccountType;

    public function setPassword(string $password): void;

    public function getFirstName(): ?string;

    public function getLastName(): ?string;

    public function getEmail(): ?string;

    public function isTokenValid(): bool;

    public function resetToken(): void;

    public function setToken(string $token): void;

    public function getToken(): ?string;
}