<?php

namespace UserAccess\Core;

interface UserInterface {

    public function __construct(string $id);

    public function getId(): string;

    public function getDisplayName(): string;

    public function authenticate($secret): bool;

    public function getEmail(): string;

    public function isLocked(): bool;

    public function hasRole(string $role): bool;

    public function getAttributes(): array;

}