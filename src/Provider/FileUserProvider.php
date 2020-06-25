<?php

namespace PragmaPHP\UserAccess\Provider;

use \PragmaPHP\UserAccess\UserAccess;
use \PragmaPHP\UserAccess\Entry\UserInterface;

class FileUserProvider extends AbstractFileEntryProvider implements UserProviderInterface {

    public function __construct(string $directory = 'data') {
        parent::__construct(UserInterface::TYPE, $directory);
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

    public function findUsers(string $attributeName, string $attributeValue): array {
        return parent::findEntries($attributeName, $attributeValue);
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