<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\UserAccess;
use \UserAccess\Core\Provider\AbstractFilebaseEntryProvider;
use \UserAccess\Core\Provider\RoleProviderInterface;
use \UserAccess\Core\Entry\RoleInterface;
use \UserAccess\Core\Entry\Role;

use \Filebase\Database;
use \Filebase\Format\Yaml;
use \Filebase\Format\Json;

class FilebaseRoleProvider extends AbstractFilebaseEntryProvider implements RoleProviderInterface {

    public function isRoleExisting(string $id): bool {
        return parent::isEntryExisting($id);
    }

    public function createRole(RoleInterface $entry) {
        parent::createEntry($entry);
    }

    public function getRole(string $id): RoleInterface {
        if ($this->isRoleExisting($id)) {
            $role = new Role($id);
            $role->setAttributes($this->db->get($id)->toArray());
            return $role;
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
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

    public function updateRole(RoleInterface $entry) {
        parent::updateEntry($entry);
    }

    public function deleteRole(string $id) {
        parent::deleteEntry($id);
    }

}