<?php

namespace UserAccess\Core\Entry;

interface UserInterface extends EntryInterface {

    const TYPE = 'USER';

    public function authenticate(string $secret): bool;

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