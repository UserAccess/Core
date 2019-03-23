<?php

namespace UserAccess\Provider;

use \UserAccess\UserAccess;
use \UserAccess\Provider\AbstractFilebaseEntryProvider;
use \UserAccess\Provider\UserProviderInterface;
use \UserAccess\Entry\UserInterface;
use \UserAccess\Entry\User;

use \Filebase\Database;
use \Filebase\Format\Yaml;
use \Filebase\Format\Json;

class FilebaseUserProvider extends AbstractFilebaseEntryProvider implements UserProviderInterface {

    public function __construct(string $directory = 'data', string $format = 'YAML') {
        parent::__construct(User::TYPE, $directory, $format);
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