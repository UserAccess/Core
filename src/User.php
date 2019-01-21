<?php

namespace UserAccess\Core;

use UserAccess\Core\UserInterface;
use UserAccess\Core\Password;

class User implements UserInterface {

    private $id = '';
    private $displayName = '';
    private $passwordHash = '';
    private $email = '';
    private $locked = false;
    private $failedLoginAttempts = 0;
    private $roles = [];

    public function __construct(string $id) {
        $this->id = $id;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getDisplayName(): string {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName) {
        $this->displayName = $displayName;
    }

    public function getPasswordHash(): string {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash) {
        $this->passwordHash = $passwordHash;
    }

    public function authenticate($secret): bool {
        return Password::verify($secret, $this->passwordHash);
    }

    public function setPassword(string $password) {
        $this->passwordHash = Password::hash($password);
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function isLocked(): bool {
        return $this->locked;
    }

    public function setLocked(bool $locked) {
        $this->locked = $locked;
    }

    public function addRole(string $role) {
        $this->roles[] = $role;
    }

    public function removeRole(string $role) {
        if (($key = array_search($role, $this->roles)) !== false) {
            unset($this->roles[$key]);
        }
    }

    public function getRoles() {
        return $this->roles;
    }

    public function hasRole(string $role): bool {
        return in_array($role, $this->roles);
    }

    public function getAttributes(): array {
        return $array = [
            'id' => $this->id,
            'displayName' => $this->displayName,
            'passwordHash' => $this->passwordHash,
            'email' => $this->email,
            'locked' => $this->locked,
            'failedLoginAttempts' => $this->failedLoginAttempts,
            'roles' => $this->roles
        ];
    }

}