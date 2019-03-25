<?php

namespace UserAccess\Entry;

interface UserInterface extends EntryInterface {

    const TYPE = 'USER';

    public function verifyPassword(string $password): bool;

    public function setPassword(string $password);

    public function getEmail(): string;

    public function setEmail(string $email);

    public function isLocked(): bool;

    public function setLocked(bool $locked);

    public function getFailedLoginAttempts(): int;

    public function setFailedLoginAttempts(int $failedLoginAttempts);

    public function hasRole(string $role): bool;

    public function addRole(string $role);

    public function removeRole(string $role);

}