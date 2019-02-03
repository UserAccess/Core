<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\Provider\UserProviderInterface;
use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Entry\User;

use \Filebase\Database;
use \Filebase\Format\Yaml;
use \Filebase\Format\Json;

class FilebaseUserProvider extends AbstractFilebaseEntryProvider implements UserProviderInterface {

    public function createUser(UserInterface $user) {
        $id = $user->getId();
        if ($this->isExisting($id)) {
            throw new \Exception('User with ' . $id . ' already available');
        } else {
            $item = $this->db->get($id);
            $item->set($user->getAttributes())->save();
        }
    }

    public function getUser(string $id): UserInterface {
        if ($this->isExisting($id)) {
            $user = new User($id);
            $user->setAttributes($this->db->get($id)->toArray());
            return $user;
        } else {
            throw new \Exception('User with ' . $id . ' not available');
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

    public function updateUser(UserInterface $user) {
        $id = $user->getId();
        if ($this->isExisting($id)) {
            $item = $this->db->get($id);
            $item->set($user->getAttributes())->save();
        } else {
            throw new \Exception('User with ' . $id . ' not available');
        }
    }

    public function deleteUser(string $id) {
        if ($this->isExisting($id)) {
            $this->db->delete($this->db->get($id));
        } else {
            throw new \Exception('User with ' . $id . ' not available');
        }
    }

}