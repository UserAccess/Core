<?php

namespace UserAccess\Core\Entry;

use \UserAccess\Core\UserAccess;
use \UserAccess\Core\Entry\Entry;
use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Util\Password;

class User extends AbstractEntry implements UserInterface {

    private $passwordHash = '';
    private $email = '';
    private $locked = false;
    private $failedLoginAttempts = 0;
    private $roles = [];

    public function setPassword(string $password) {
        if (empty($password)) {
            throw new \Exception(UserAccess::EXCEPTION_INVALID_PASSWORD);
        }
        $password = \trim($password);
        if (\strlen($password) < 7) {
            throw new \Exception(UserAccess::EXCEPTION_INVALID_PASSWORD);
        }
        $this->passwordHash = Password::hash($password);
    }

    public function authenticate(string $secret): bool {
        return Password::verify($secret, $this->passwordHash);
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email) {
        if (!empty($email) && !filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
            throw new \Exception(UserAccess::EXCEPTION_INVALID_EMAIL);
        }
        $this->email = \trim(\strtolower($email));
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
            $this->setPassword($attributes['password']);
        }
        if (array_key_exists('email', $attributes)) {
            $this->setEmail($attributes['email']);
        }
        if (array_key_exists('locked', $attributes)) {
            $this->setLocked($attributes['locked']);
        }
        if (array_key_exists('failedLoginAttempts', $attributes)) {
            $this->setFailedLoginAttempts($attributes['failedLoginAttempts']);
        }
        if (array_key_exists('roles', $attributes)) {
            $this->setRoles($attributes['roles']);
        }
    }

    //////////////////////////////////////////////////

    private function setPasswordHash(string $passwordHash) {
        $this->passwordHash = \trim($passwordHash);
    }

}