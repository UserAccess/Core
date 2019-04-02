<?php

namespace UserAccess\Auth;

use \UserAccess\UserAccess;
use \UserAccess\Entry\UserInterface;

class SessionAuthenticator implements AuthenticatorInterface {

    private $userProvider;

    public function __construct(UserProviderInterface $userProvider) {
        $this->userProvider = $userProvider;
    }

    public function login(string $userName, string $password) {
        $userName = \trim(\strtolower($userName));
        $password = \trim($password);
        if (empty($userName) || empty($password) || !$this->isUniqueNameExisting($userName)) {
            throw new \Exception(UserAccess::EXCEPTION_AUTHENTICATION_FAILED);
        }
        $users = $this->userProvider->findUsers('uniqueName', $uniqueName, UserAccess::COMPARISON_EQUAL_IGNORE_CASE);
        $user;
        if (empty($users) || \count($users) > 1) {
            throw new \Exception(UserAccess::EXCEPTION_AUTHENTICATION_FAILED);
        } else {
            $user = current($users);
        }
        if (!$user->isActive() || $user->getLoginAttempts() > 10) {
            throw new \Exception(UserAccess::EXCEPTION_AUTHENTICATION_FAILED);
        }
        if ($user->verifyPassword($secret)){
            \session_start();
            \session_regenerate_id();
            $_SESSION[UserAccess::SESSION_USERACCESS_USERID] = $user->getId();
            $_SESSION[UserAccess::SESSION_USERACCESS_AUTHENTICATED] = true;
        } else {
            throw new \Exception(UserAccess::EXCEPTION_AUTHENTICATION_FAILED);
        }
    }

    public function logout() {
        unset($_SESSION[UserAccess::SESSION_USERACCESS_USERID]);
        unset($_SESSION[UserAccess::SESSION_USERACCESS_AUTHENTICATED]);
        \session_start();
        \session_regenerate_id();
    }

}