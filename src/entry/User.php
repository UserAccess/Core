<?php

namespace UserAccess\Core\Entry;

use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Util\Password;

class User implements UserInterface {

    private $id = '';
    private $displayName = '';
    private $passwordHash = '';
    private $email = '';
    private $locked = false;
    private $failedLoginAttempts = 0;
    private $roles = [];

    public function __construct(string $id) {
        if (empty($id)) {
            throw new \Exception('ID mandatory');
        }
        $this->id = strtolower($id);
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

    public function authenticate(string $secret): bool {
        return Password::verify($secret, $this->passwordHash);
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email) {
        if (!empty($email) && !filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('E-Mail validation failed');
        }
        $this->email = $email;
    }

    public function isLocked(): bool {
        return $this->locked;
    }

    public function setLocked(bool $locked) {
        $this->locked = $locked;
    }

    public function getFailedLoginAttempts(): int {
        return $this->failedLoginAttempts;
    }

    public function setFailedLoginAttempts(int $failedLoginAttempts) {
        $this->failedLoginAttempts = $failedLoginAttempts;
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

    public function setAttributes($attributes) {
        // id is read only
        $this->displayName = array_key_exists('displayName', $attributes) ? $attributes['displayName'] : '';
        $this->passwordHash = array_key_exists('passwordHash', $attributes) ? $attributes['passwordHash'] : '';
        $this->email = array_key_exists('email', $attributes) ? $attributes['email'] : '';
        $this->locked = array_key_exists('locked', $attributes) ? $attributes['locked'] : '';
        $this->failedLoginAttempts = array_key_exists('failedLoginAttempts', $attributes) ? $attributes['failedLoginAttempts'] : '';
        $this->roles = array_key_exists('roles', $attributes) ? $attributes['roles'] : '';
    }

}