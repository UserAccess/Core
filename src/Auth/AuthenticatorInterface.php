<?php

namespace UserAccess\Auth;

use \UserAccess\Entry\UserInterface;

interface AuthenticatorInterface {

    public function login(string $userName, string $secret);

    public function logout();

}