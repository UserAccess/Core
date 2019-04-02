<?php

namespace UserAccess\Provider;

use \UserAccess\UserAccess;
use \UserAccess\Entry\GroupInterface;

use \Filebase\Database;
use \Filebase\Format\Yaml;
use \Filebase\Format\Json;

class FilebaseGroupProvider extends AbstractFilebaseEntryProvider implements GroupProviderInterface {

    public function __construct(string $directory = 'data', string $format = 'YAML') {
        parent::__construct(GroupInterface::TYPE, $directory, $format);
    }

    public function createGroup(GroupInterface $entry): GroupInterface {
        return parent::createEntry($entry);
    }

    public function getGroup(string $id): GroupInterface {
        return parent::getEntry($id);
    }

    public function getGroups(): array {
        return parent::getEntries();
    }

    public function findGroups(string $attributeName, string $attributeValue, string $comparisonOperator): array {
        return parent::findEntries($attributeName, $attributeValue, $comparisonOperator);
    }

    public function updateGroup(GroupInterface $entry): GroupInterface {
        return parent::updateEntry($entry);
    }

    public function deleteGroup(string $id) {
        parent::deleteEntry($id);
    }

    public function deleteGroups() {
        parent::deleteEntries();
    }

}