<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\Provider\ProviderInterface;
use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Entry\User;

class StaticProvider implements ProviderInterface {

    private $users = [];

    public function __construct() {
    }

    public function isUserExisting(string $id): bool {
        if (isset($this->users[$id])) {
            return true;        
        } else {
            return false;
        }
    }

    public function createUser(UserInterface $user) {
        $id = $user->getId();
        if ($this->isUserExisting($id)) {
            throw new \Exception('User with ' . $id . ' already available');
        } else {
            $this->users[$id] = $user;
        }
    }

    public function readUser(string $id): UserInterface {
        if ($this->isUserExisting($id)) {
            return $this->users[$id];
        } else {
            throw new \Exception('User with ' . $id . ' not available');
        }
    }

    public function updateUser(UserInterface $user) {
        $id = $user->getId();
        if ($this->isUserExisting($id)) {
            $this->users[$id] = $user;
        } else {
            throw new \Exception('User with ' . $id . ' not available');
        }
    }

    public function deleteUser(string $id) {
        $id = $user->getId();
        if ($this->isUserExisting($id)) {
            unset($this->users[$id]);
        } else {
            throw new \Exception('User with ' . $id . ' not available');
        }
    }

}