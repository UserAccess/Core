<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\Provider\RoleProviderInterface;
use \UserAccess\Core\Entry\RoleInterface;
use \UserAccess\Core\Entry\Role;

use \Filebase\Database;
use \Filebase\Format\Yaml;
use \Filebase\Format\Json;

class FilebaseRoleProvider extends AbstractFilebaseEntryProvider implements RoleProviderInterface {

    public function createRole(RoleInterface $role) {
        $id = $role->getId();
        if ($this->isExisting($id)) {
            throw new \Exception('Role with ' . $id . ' already available');
        } else {
            $item = $this->db->get($id);
            $item->set($role->getAttributes())->save();
        }
    }

    public function getRole(string $id): RoleInterface {
        if ($this->isExisting($id)) {
            $role = new Role($id);
            $role->setAttributes($this->db->get($id)->toArray());
            return $role;
        } else {
            throw new \Exception('Role with ' . $id . ' not available');
        }
    }

    public function getAllRoles(): array {
        $result = [];
        $items = $this->db->findAll();
        foreach($items as $item){
            $role = new Role($item->id);
            $role->setAttributes($item->toArray());
            $result[] = $role;
        }
        return $result;
    }

    public function updateRole(RoleInterface $role) {
        $id = $role->getId();
        if ($this->isExisting($id)) {
            $item = $this->db->get($id);
            $item->set($role->getAttributes())->save();
        } else {
            throw new \Exception('Role with ' . $id . ' not available');
        }
    }

    public function deleteRole(string $id) {
        if ($this->isExisting($id)) {
            $this->db->delete($this->db->get($id));
        } else {
            throw new \Exception('Role with ' . $id . ' not available');
        }
    }

}