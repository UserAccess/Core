<?php

namespace UserAccess\Provider;

use \UserAccess\UserAccess;
use \UserAccess\Entry\UserInterface;

class StaticUserProvider extends AbstractStaticEntryProvider implements UserProviderInterface {

    function __construct() {
        parent::__construct(UserInterface::TYPE);
    }

    public function createUser(UserInterface $entry): UserInterface {
        return parent::createEntry($entry);
    }

    public function getUser(string $id): UserInterface {
        return parent::getEntry($id);
    }

    public function getUsers(): array {
        return parent::getEntries();
    }

    public function findUsers(string $attributeName, string $attributeValue, string $comparisonOperator): array {
        return parent::findEntries($attributeName, $attributeValue, $comparisonOperator);
    }

    public function updateUser(UserInterface $entry): UserInterface {
        return parent::updateEntry($entry);
    }

    public function deleteUser(string $id) {
        parent::deleteEntry($id);
    }

    public function deleteUsers() {
        parent::deleteEntries();
    }

}