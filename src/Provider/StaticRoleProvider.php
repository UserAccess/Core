<?php

namespace UserAccess\Provider;

use \UserAccess\UserAccess;
use \UserAccess\Provider\AbstractStaticEntryProvider;
use \UserAccess\Provider\RoleProviderInterface;
use \UserAccess\Entry\RoleInterface;
use \UserAccess\Entry\Role;

class StaticRoleProvider extends AbstractStaticEntryProvider implements RoleProviderInterface {

    function __construct() {
        parent::__construct(Role::TYPE);
    }

    public function isRoleExisting(string $id): bool {
        return parent::isEntryExisting($id);
    }

    public function createRole(RoleInterface $entry) {
        parent::createEntry($entry);
    }

    public function getRole(string $id): RoleInterface {
        return parent::getEntry($id);
    }

    public function getRoles(): array {
        return parent::getEntries();
    }

    public function findRoles(string $attributeName, string $attributeValue, string $comparisonOperator): array {
        return parent::findEntries($attributeName, $attributeValue, $comparisonOperator);
    }

    public function updateRole(RoleInterface $entry) {
        parent::updateEntry($entry);
    }

    public function deleteRole(string $id) {
        parent::deleteEntry($id);
    }

}