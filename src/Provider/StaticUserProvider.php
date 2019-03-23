<?php

namespace UserAccess\Provider;

use \UserAccess\UserAccess;
use \UserAccess\Provider\AbstractStaticEntryProvider;
use \UserAccess\Provider\UserProviderInterface;
use \UserAccess\Entry\UserInterface;
use \UserAccess\Entry\User;

class StaticUserProvider extends AbstractStaticEntryProvider implements UserProviderInterface {

    function __construct() {
        parent::__construct(User::TYPE);
    }

    public function isUserExisting(string $id): bool {
        return parent::isEntryExisting($id);
    }

    public function createUser(UserInterface $entry) {
        parent::createEntry($entry);
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

    public function updateUser(UserInterface $entry) {
        parent::updateEntry($entry);
    }

    public function deleteUser(string $id) {
        parent::deleteEntry($id);
    }

}