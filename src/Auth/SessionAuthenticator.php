<?php

namespace UserAccess\Auth;

use \UserAccess\UserAccess;
use \UserAccess\Auth\AuthenticatorInterface;
use \UserAccess\Entry\UserInterface;

class SessionAuthenticator implements AuthenticatorInterface {

    public function login(UserInterface $user, string $secret): bool {
        if ($user->verifyPassword($secret)){
            \session_start();
            \session_regenerate_id();
            $_SESSION[UserAccess::SESSION_USERACCESS_USERID] = $user->getId();
            $_SESSION[UserAccess::SESSION_USERACCESS_AUTHENTICATED] = true;
            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        $_SESSION[UserAccess::SESSION_USERACCESS_USERID] = NULL;
        $_SESSION[UserAccess::SESSION_USERACCESS_AUTHENTICATED] = NULL;
        \session_start();
        \session_regenerate_id();
    }

}