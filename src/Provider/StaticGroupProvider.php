<?php

namespace PragmaPHP\UserAccess\Provider;

use \PragmaPHP\UserAccess\UserAccess;
use \PragmaPHP\UserAccess\Entry\GroupInterface;

class StaticGroupProvider extends AbstractStaticEntryProvider implements GroupProviderInterface {

    function __construct() {
        parent::__construct(GroupInterface::TYPE);
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