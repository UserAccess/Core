<?php

namespace UserAccess\Core;

use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Provider\UserProviderInterface;
use \UserAccess\Core\Provider\RoleProviderInterface;
use \UserAccess\Core\Util\AuditLog;

class UserAccess {

    private $userProvider;
    private $fallbackUserProvider;
    private $roleProvider;

    public function __construct(
        UserProviderInterface $userProvider, 
        UserProviderInterface $fallbackUserProvider = null, 
        RoleProviderInterface $roleProvider = null,
        AuditLog $logger = null) {
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
        if ($this->fallbackUserProvider) {
            return $this->fallbackUserProvider;
        } else {
            throw new \Exception('Fallback user provider not available');
        }
    }

    public function getRoleProvider(): RoleProviderInterface {
        return $this->roleProvider;
        if ($this->roleProvider) {
            return $this->roleProvider;
        } else {
            throw new \Exception('Role provider not available');
        }
    }

}