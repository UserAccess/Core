<?php

namespace UserAccess\Core;

use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Provider\UserProviderInterface;
use \UserAccess\Core\Provider\RoleProviderInterface;

class UserAccess {

    private $userProvider;
    private $fallbackUserProvider;
    private $roleProvider;

    public function __construct(UserProviderInterface $userProvider, UserProviderInterface $fallbackUserProvider = null, RoleProviderInterface $roleProvider = null) {
        if (empty($userProvider)) {
            throw new \Exception('User provider mandatory');
        }
        $this->userProvider = $userProvider;
        if ($fallbackUserProvider) {
            $this->fallbackUserProvider = $fallbackUserProvider;
        }
        if ($roleProvider) {
            $this->roleProvider = $roleProvider;
        }
    }

    public function getUserProvider(): UserProviderInterface {
        return $this->userProvider;
    }

    public function getFallbackUserProvider(): UserProviderInterface {
        return $this->fallbackUserProvider;
    }

    public function getRoleProvider(): RoleProviderInterface {
        return $this->roleProvider;
    }

}