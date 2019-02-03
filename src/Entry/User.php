<?php

namespace UserAccess\Core\Entry;

use \UserAccess\Core\Entry\Entry;
use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Util\Password;

class User extends AbstractEntry implements UserInterface {

    protected $type = 'User';
    private $passwordHash = '';
    private $email = '';
    private $locked = false;
    private $failedLoginAttempts = 0;
    private $roles = [];

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

    public function setRoles(array $roles) {
        $this->roles = $roles;
    }

    public function hasRole(string $role): bool {
        return in_array($role, $this->roles);
    }

    public function getAttributes(): array {
        $attributes = parent::getAttributes();
        $attributes['passwordHash'] = $this->passwordHash;
        $attributes['email'] = $this->email;
        $attributes['locked'] = $this->locked;
        $attributes['failedLoginAttempts'] = $this->failedLoginAttempts;
        $attributes['roles'] = $this->roles;
        return $attributes;
    }

    public function setAttributes(array $attributes) {
        parent::setAttributes($attributes);
        if (!empty($attributes['passwordHash'])) {
            $this->setPasswordHash($attributes['passwordHash']);
        } else if (!empty($attributes['password'])) {
            $this->setPasswordHash(Password::hash($attributes['password']));
        }
        if (!empty($attributes['email'])) {
            $this->setEmail($attributes['email']);
        }
        if (!empty($attributes['locked'])) {
            $this->setLocked($attributes['locked']);
        }
        if (!empty($attributes['failedLoginAttempts'])) {
            $this->setFailedLoginAttempts($attributes['failedLoginAttempts']);
        }
        if (!empty($attributes['roles'])) {
            $this->setRoles($attributes['roles']);
        }
    }

}