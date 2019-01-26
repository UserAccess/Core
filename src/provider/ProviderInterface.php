<?php

namespace UserAccess\Core\Provider;

use UserAccess\Core\Entry\UserInterface;

interface ProviderInterface {

    public function isUserExisting(string $id): bool;

    public function createUser(UserInterface $user);

    public function readUser(string $id): ?UserInterface;

    public function updateUser(UserInterface $user);

    public function deleteUser(string $id);

}