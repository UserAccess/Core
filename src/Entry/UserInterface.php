<?php

namespace UserAccess\Entry;

interface UserInterface extends EntryInterface {

    const TYPE = 'User';

    public function getUserName(): string;

    // public function setUserName(string $userName);

    public function verifyPassword(string $password): bool;

    public function setPassword(string $password);

    public function getEmail(): string;

    public function setEmail(string $email);

    public function isActive(): bool;

    public function setActive(bool $active);

    public function getLoginAttempts(): int;

    public function setLoginAttempts(int $LoginAttempts);

    public function hasRole(string $role): bool;

    public function addRole(string $role);

    public function removeRole(string $role);

}