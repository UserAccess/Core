<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\Entry\RoleInterface;
use \UserAccess\Core\Provider\EntryProviderInterface;

interface RoleProviderInterface extends EntryProviderInterface {

    public function createRole(RoleInterface $user);

    public function getRole(string $id): ?RoleInterface;

    public function getAllRoles(): ?array;

    public function updateRole(RoleInterface $user);

    public function deleteRole(string $id);

}