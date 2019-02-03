<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\Provider\UserProviderInterface;
use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Entry\User;

class StaticUserProvider implements UserProviderInterface {

    private $users = [];

    public function __construct() {
    }

    public function isExisting(string $id): bool {
        if (isset($this->users[$id])) {
            return true;        
        } else {
            return false;
        }
    }

    public function createUser(UserInterface $user) {
        $id = $user->getId();
        if ($this->isExisting($id)) {
            throw new \Exception('User with ' . $id . ' already available');
        } else {
            $this->users[$id] = $user;
        }
    }

    public function getUser(string $id): UserInterface {
        if ($this->isExisting($id)) {
            return $this->users[$id];
        } else {
            throw new \Exception('User with ' . $id . ' not available');
        }
    }

    public function getAllUsers(): array {
        return $this->users;
    }

    public function updateUser(UserInterface $user) {
        $id = $user->getId();
        if ($this->isExisting($id)) {
            $this->users[$id] = $user;
        } else {
            throw new \Exception('User with ' . $id . ' not available');
        }
    }

    public function deleteUser(string $id) {
        $id = $user->getId();
        if ($this->isExisting($id)) {
            unset($this->users[$id]);
        } else {
            throw new \Exception('User with ' . $id . ' not available');
        }
    }

}