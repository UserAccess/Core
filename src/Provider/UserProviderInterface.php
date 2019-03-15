<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Provider\EntryProviderInterface;

interface UserProviderInterface extends EntryProviderInterface {

    public function isUserExisting(string $id): bool;

    public function createUser(UserInterface $user);

    public function getUser(string $id): ?UserInterface;

    public function getUsers(): ?array;

    public function findUsers(string $attributeName, string $attributeValue, string $comparisonOperator): ?array;

    public function updateUser(UserInterface $user);

    public function deleteUser(string $id);

}