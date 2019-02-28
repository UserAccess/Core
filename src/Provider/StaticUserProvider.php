<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\UserAccess;
use \UserAccess\Core\Provider\AbstractStaticEntryProvider;
use \UserAccess\Core\Provider\UserProviderInterface;
use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Entry\User;

class StaticUserProvider extends AbstractStaticEntryProvider implements UserProviderInterface {

    public function createUser(UserInterface $entry) {
        parent::createEntry($entry);
    }

    public function isExisting(string $id): bool {
        if (isset($this->entries[$id])) {
            return true;        
        } else {
            return false;
        }
    }

    public function getUser(string $id): UserInterface {
        if ($this->isExisting($id)) {
            return $this->entries[$id];
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function getAllUsers(): array {
        return $this->entries;
    }

    public function updateUser(UserInterface $entry) {
        parent::updateEntry($entry);
    }

    public function deleteUser(string $id) {
        parent::deleteEntry($id);
    }

}