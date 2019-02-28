<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\UserAccess;
use \UserAccess\Core\Provider\AbstractFilebaseEntryProvider;
use \UserAccess\Core\Provider\UserProviderInterface;
use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Entry\User;

use \Filebase\Database;
use \Filebase\Format\Yaml;
use \Filebase\Format\Json;

class FilebaseUserProvider extends AbstractFilebaseEntryProvider implements UserProviderInterface {

    public function isUserExisting(string $id): bool {
        return parent::isEntryExisting($id);
    }

    public function createUser(UserInterface $entry) {
        parent::createEntry($entry);
    }

    public function getUser(string $id): UserInterface {
        if ($this->isUserExisting($id)) {
            $user = new User($id);
            $user->setAttributes($this->db->get($id)->toArray());
            return $user;
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function getAllUsers(): array {
        $result = [];
        $items = $this->db->findAll();
        foreach($items as $item){
            $user = new User($item->id);
            $user->setAttributes($item->toArray());
            $result[] = $user;
        }
        return $result;
    }

    public function updateUser(UserInterface $entry) {
        parent::updateEntry($entry);
    }

    public function deleteUser(string $id) {
        parent::deleteEntry($id);
    }

}