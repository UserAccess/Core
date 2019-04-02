<?php

namespace UserAccess\Provider;

use \UserAccess\Entry\GroupInterface;

interface GroupProviderInterface extends EntryProviderInterface {

    public function createGroup(GroupInterface $group): GroupInterface;

    public function getGroup(string $id): GroupInterface;

    public function getGroups(): ?array;

    public function findGroups(string $attributeName, string $attributeValue, string $comparisonOperator): array;

    public function updateGroup(GroupInterface $group): GroupInterface;

    public function deleteGroup(string $id);

    public function deleteGroups();

}