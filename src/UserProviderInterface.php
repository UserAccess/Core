<?php

namespace UserAccess\Core;

use UserAccess\Core\UserInterface;

interface UserProviderInterface {

    public function getUserById(string $id): UserInterface;

}