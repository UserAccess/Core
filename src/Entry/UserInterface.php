<?php

namespace PragmaPHP\UserAccess\Entry;

interface UserInterface extends EntryInterface {

    const TYPE = 'User';

    public function getUserName(): string;

    public function getGivenName(): string;

    public function setGivenName(string $givenName);

    public function getFamilyName(): string;

    public function setFamilyName(string $familyName);

    public function verifyPassword(string $password): bool;

    public function setPassword(string $password);

    public function setPasswordHash(string $passwordHash);

    public function getEmails(): array;

    public function setEmails(array $emails);

    public function isActive(): bool;

    public function setActive(bool $active);

    public function getLoginAttempts(): int;

    public function setLoginAttempts(int $LoginAttempts);

    public function getRoles(): array;

    public function setRoles(array $roles);

    public function hasRole(string $role): bool;

    public function addRole(string $role);

    public function removeRole(string $role);

}