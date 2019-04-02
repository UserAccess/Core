<?php

namespace UserAccess\Provider;

use \UserAccess\Entry\UserInterface;

interface UserProviderInterface extends EntryProviderInterface {

    public function createUser(UserInterface $user): UserInterface;

    public function getUser(string $id): UserInterface;

    public function getUsers(): array;

    public function findUsers(string $attributeName, string $attributeValue, string $comparisonOperator): array;

    public function updateUser(UserInterface $user): UserInterface;

    public function deleteUser(string $id);

    public function deleteUsers();

}