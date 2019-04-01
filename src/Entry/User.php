<?php

namespace UserAccess\Entry;

use \UserAccess\UserAccess;
use \UserAccess\Entry\Entry;
use \UserAccess\Entry\UserInterface;
use \UserAccess\Util\Password;

class User extends AbstractEntry implements UserInterface {

    private $passwordHash = '';
    private $email = '';
    private $active = true;
    private $loginAttempts = 0;
    private $roles = [];

    public function getUserName(): string {
        return parent::getUniqueName();
    }

    public function verifyPassword(string $password): bool {
        return Password::verify($password, $this->passwordHash);
    }

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

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email) {
        if (!empty($email) && !filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
            throw new \Exception(UserAccess::EXCEPTION_INVALID_EMAIL);
        }
        $this->email = \trim(\strtolower($email));
    }

    public function isActive(): bool {
        return $this->active;
    }

    public function setActive(bool $active) {
        $this->active = $active;
    }

    public function getLoginAttempts(): int {
        return $this->loginAttempts;
    }

    public function setLoginAttempts(int $loginAttempts) {
        $this->loginAttempts = $loginAttempts;
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
        $attributes['userName'] = $this->getUniqueName();
        $attributes['passwordHash'] = $this->getPasswordHash();
        $attributes['email'] = $this->getEmail();
        $attributes['active'] = $this->isActive();
        $attributes['loginAttempts'] = $this->getLoginAttempts();
        $attributes['roles'] = $this->getRoles();
        return $attributes;
    }

    public function setAttributes(array $attributes) {
        parent::setAttributes($attributes);
        // if (array_key_exists('userName', $attributes)) {
        //     $this->setUserName($attributes['userName']);
        // }
        if (!empty($attributes['passwordHash'])) {
            $this->setPasswordHash($attributes['passwordHash']);
        } else if (!empty($attributes['password'])) {
            $this->setPassword($attributes['password']);
        }
        if (array_key_exists('email', $attributes)) {
            $this->setEmail($attributes['email']);
        }
        if (array_key_exists('active', $attributes)) {
            $this->setActive($attributes['active']);
        }
        if (array_key_exists('loginAttempts', $attributes)) {
            $this->setLoginAttempts($attributes['loginAttempts']);
        }
        if (array_key_exists('roles', $attributes)) {
            $this->setRoles($attributes['roles']);
        }
    }

    //////////////////////////////////////////////////

    private function setPasswordHash(string $passwordHash) {
        $this->passwordHash = \trim($passwordHash);
    }

    private function getPasswordHash(): string {
        return $this->passwordHash;
    }

}