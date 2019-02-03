<?php

namespace UserAccess\Core\Entry;

interface UserInterface {

    public function authenticate(string $secret): bool;

    public function getEmail(): string;

    public function isLocked(): bool;

    public function getFailedLoginAttempts(): int;

    public function hasRole(string $role): bool;

    public function getAttributes(): array;

}