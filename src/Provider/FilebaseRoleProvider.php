<?php

namespace UserAccess\Provider;

use \UserAccess\UserAccess;
use \UserAccess\Entry\RoleInterface;

use \Filebase\Database;
use \Filebase\Format\Yaml;
use \Filebase\Format\Json;

class FilebaseRoleProvider extends AbstractFilebaseEntryProvider implements RoleProviderInterface {

    public function __construct(string $directory = 'data', string $format = 'YAML') {
        parent::__construct(RoleInterface::TYPE, $directory, $format);
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

    public function findRoles(string $attributeName, string $attributeValue, string $comparisonOperator): array {
        return parent::findEntries($attributeName, $attributeValue, $comparisonOperator);
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