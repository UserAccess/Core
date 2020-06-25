<?php

namespace PragmaPHP\UserAccess\Provider;

use \PragmaPHP\UserAccess\UserAccess;
use \PragmaPHP\UserAccess\Entry\RoleInterface;

class FileRoleProvider extends AbstractFileEntryProvider implements RoleProviderInterface {

    public function __construct(string $directory = 'data') {
        parent::__construct(RoleInterface::TYPE, $directory);
    }

    public function createRole(RoleInterface $entry): RoleInterface {
        return parent::createEntry($entry);
    }

    public function getRole(string $id): RoleInterface {
        return parent::getEntry($id);
    }

    public function getRoles(): array {
        return parent::getEntries();
    }

    public function findRoles(string $attributeName, string $attributeValue): array {
        return parent::findEntries($attributeName, $attributeValue);
    }

    public function updateRole(RoleInterface $entry): RoleInterface {
        return parent::updateEntry($entry);
    }

    public function deleteRole(string $id) {
        parent::deleteEntry($id);
    }

    public function deleteRoles() {
        parent::deleteEntries();
    }

}