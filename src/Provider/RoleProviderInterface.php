<?php

namespace PragmaPHP\UserAccess\Provider;

use \PragmaPHP\UserAccess\Entry\RoleInterface;

interface RoleProviderInterface extends EntryProviderInterface {

    public function createRole(RoleInterface $role): RoleInterface;

    public function getRole(string $id): RoleInterface;

    public function getRoles(): ?array;

    public function findRoles(string $attributeName, string $attributeValue): array;

    public function updateRole(RoleInterface $role): RoleInterface;

    public function deleteRole(string $id);

    public function deleteRoles();

}