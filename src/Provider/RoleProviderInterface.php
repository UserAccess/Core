<?php

namespace UserAccess\Provider;

use \UserAccess\Entry\RoleInterface;
use \UserAccess\Provider\EntryProviderInterface;

interface RoleProviderInterface extends EntryProviderInterface {

    public function createRole(RoleInterface $role): RoleInterface;

    public function getRole(string $id): RoleInterface;

    public function getRoles(): ?array;

    public function findRoles(string $attributeName, string $attributeValue, string $comparisonOperator): array;

    public function updateRole(RoleInterface $role): RoleInterface;

    public function deleteRole(string $uniqueName);

    public function deleteRoles();

}