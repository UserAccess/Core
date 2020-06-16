<?php

namespace UserAccess\Provider;

use \UserAccess\UserAccess;
use \UserAccess\Entry\GroupInterface;

class FileGroupProvider extends AbstractFileEntryProvider implements GroupProviderInterface {

    public function __construct(string $directory = 'data') {
        parent::__construct(GroupInterface::TYPE, $directory);
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

    public function findGroups(string $attributeName, string $attributeValue): array {
        return parent::findEntries($attributeName, $attributeValue);
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