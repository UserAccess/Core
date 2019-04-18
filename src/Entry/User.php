<?php

namespace UserAccess\Entry;

use \UserAccess\UserAccess;
use \UserAccess\Util\Password;

class User extends AbstractEntry implements UserInterface {

    private $givenName = '';
    private $familyName = '';
    private $passwordHash = '';
    private $email = '';
    private $active = true;
    private $loginAttempts = 0;
    private $roles = [];
    private $groups = [];

    public function getUserName(): string {
        return parent::getUniqueName();
    }

    public function getGivenName(): string {
        return $this->givenName;
    }

    public function setGivenName(string $givenName) {
        $this->givenName = \trim($givenName);
    }

    public function getFamilyName(): string {
        return $this->familyName;
    }

    public function setFamilyName(string $familyName) {
        $this->familyName = \trim($familyName);
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

    public function setPasswordHash(string $passwordHash) {
        if (empty($passwordHash)) {
            throw new \Exception(UserAccess::EXCEPTION_INVALID_PASSWORD);
        }
        $this->passwordHash = \trim($passwordHash);
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

    public function getEmails(): array {
        return [$this->getEmail()];
    }

    public function setEmails(array $emails) {
        if (!empty($emails)) {
            $this->setEmail(current($emails));
        }
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

    public function getGroups(): array {
        return $this->groups;
    }

    public function setGroups(array $groups) {
        $this->groups = $groups;
    }

    public function hasGroup(string $group): bool {
        return in_array($group, $this->groups);
    }

    public function addGroup(string $group) {
        $this->groups[] = $group;
    }

    public function removeGroup(string $group) {
        if (($key = array_search($group, $this->groups)) !== false) {
            unset($this->groups[$key]);
        }
    }

    public function getRoles(): array {
        return $this->roles;
    }

    public function setRoles(array $roles) {
        $this->roles = $roles;
    }

    public function hasRole(string $role): bool {
        return in_array($role, $this->roles);
    }

    public function addRole(string $role) {
        $this->roles[] = $role;
    }

    public function removeRole(string $role) {
        if (($key = array_search($role, $this->roles)) !== false) {
            unset($this->roles[$key]);
        }
    }

    public function getAttributes(): array {
        $attributes = parent::getAttributes();
        $attributes['userName'] = $this->getUniqueName();
        $attributes['givenName'] = $this->getGivenName();
        $attributes['familyName'] = $this->getFamilyName();
        $attributes['passwordHash'] = $this->getPasswordHash();
        $attributes['email'] = $this->getEmail();
        $attributes['emails'] = $this->getEmails();
        $attributes['active'] = $this->isActive();
        $attributes['loginAttempts'] = $this->getLoginAttempts();
        $attributes['groups'] = $this->getGroups();
        $attributes['roles'] = $this->getRoles();
        return $attributes;
    }

    public function setAttributes(array $attributes) {
        parent::setAttributes($attributes);
        // if (array_key_exists('userName', $attributes)) {
        //     $this->setUserName($attributes['userName']);
        // }
        if (array_key_exists('givenName', $attributes)) {
            $this->setGivenName($attributes['givenName']);
        }
        if (array_key_exists('familyName', $attributes)) {
            $this->setFamilyName($attributes['familyName']);
        }
        if (!empty($attributes['passwordHash'])) {
            $this->setPasswordHash($attributes['passwordHash']);
        } else if (!empty($attributes['password'])) {
            $this->setPassword($attributes['password']);
        }
        if (array_key_exists('email', $attributes)) {
            $this->setEmail($attributes['email']);
        }
        if (array_key_exists('emails', $attributes)) {
            $this->setEmails($attributes['emails']);
        }
        if (array_key_exists('active', $attributes)) {
            $this->setActive($attributes['active']);
        }
        if (array_key_exists('loginAttempts', $attributes)) {
            $this->setLoginAttempts($attributes['loginAttempts']);
        }
        if (array_key_exists('groups', $attributes)) {
            $this->setRoles($attributes['groups']);
        }
        if (array_key_exists('roles', $attributes)) {
            $this->setRoles($attributes['roles']);
        }
    }

    //////////////////////////////////////////////////

    private function getPasswordHash(): string {
        return $this->passwordHash;
    }

}