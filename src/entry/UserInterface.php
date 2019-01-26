<?php

namespace UserAccess\Core\Entry;

interface UserInterface {

    public function __construct(string $id);

    public function getId(): string;

    public function getDisplayName(): string;

    public function authenticate(string $secret): bool;

    public function getEmail(): string;

    public function isLocked(): bool;

    public function getFailedLoginAttempts(): int;

    public function hasRole(string $role): bool;

    public function getAttributes(): array;

}