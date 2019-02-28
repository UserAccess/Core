<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\UserAccess;
use \UserAccess\Core\Provider\AbstractStaticEntryProvider;
use \UserAccess\Core\Provider\RoleProviderInterface;
use \UserAccess\Core\Entry\RoleInterface;
use \UserAccess\Core\Entry\Role;

class StaticRoleProvider extends AbstractStaticEntryProvider implements RoleProviderInterface {

    public function createRole(RoleInterface $entry) {
        parent::createEntry($entry);
    }

    public function isExisting(string $id): bool {
        if (isset($this->entries[$id])) {
            return true;        
        } else {
            return false;
        }
    }

    public function getRole(string $id): RoleInterface {
        if ($this->isExisting($id)) {
            return $this->entries[$id];
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function getAllRoles(): array {
        return $this->entries;
    }

    public function updateRole(RoleInterface $entry) {
        parent::updateEntry($entry);
    }

    public function deleteRole(string $id) {
        parent::deleteEntry($id);
    }

}