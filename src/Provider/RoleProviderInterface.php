<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\Entry\RoleInterface;
use \UserAccess\Core\Provider\EntryProviderInterface;

interface RoleProviderInterface extends EntryProviderInterface {

    public function isRoleExisting(string $id): bool;

    public function createRole(RoleInterface $role);

    public function getRole(string $id): ?RoleInterface;

    public function getRoles(): ?array;

    public function findRoles(string $attributeName, string $attributeValue, string $comparisonOperator): ?array;

    public function updateRole(RoleInterface $role);

    public function deleteRole(string $id);

}