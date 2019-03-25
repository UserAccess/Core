<?php

namespace UserAccess\Auth;

use \UserAccess\Entry\UserInterface;

interface AuthenticatorInterface {

    public function login(UserInterface $user, string $secret): bool;

    public function logout();

}