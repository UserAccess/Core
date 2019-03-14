<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\UserAccess;
use \UserAccess\Core\Provider\AbstractStaticEntryProvider;
use \UserAccess\Core\Provider\RoleProviderInterface;
use \UserAccess\Core\Entry\RoleInterface;
use \UserAccess\Core\Entry\Role;

class StaticRoleProvider extends AbstractStaticEntryProvider implements RoleProviderInterface {

    public function isRoleExisting(string $id): bool {
        return parent::isEntryExisting($id);
    }

    public function createRole(RoleInterface $entry) {
        parent::createEntry($entry);
    }

    public function getRole(string $id): RoleInterface {
        return parent::getEntry($id);
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