<?php

namespace UserAccess\Core;

use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Provider\UserProviderInterface;
use \UserAccess\Core\Provider\StaticUserProvider;
use \UserAccess\Core\Provider\RoleProviderInterface;
use \UserAccess\Core\Provider\StaticRoleProvider;
use \UserAccess\Core\Util\AuditLog;

class UserAccess {

    private $userProvider;
    private $inbuiltUserProvider;
    private $roleProvider;
    private $inbuilRoleProvider;

    const EXCEPTION_MISSING_ID = 'EXCEPTION_MISSING_ID';
    const EXCEPTION_INVALID_ID = 'EXCEPTION_INVALID_ID';
    const EXCEPTION_INVALID_EMAIL = 'EXCEPTION_INVALID_EMAIL';
    const EXCEPTION_ENTRY_ALREADY_EXIST = 'EXCEPTION_ENTRY_ALREADY_EXIST';
    const EXCEPTION_ENTRY_NOT_EXIST = 'EXCEPTION_ENTRY_NOT_EXIST';
    const EXCEPTION_ENTRY_READONLY = 'EXCEPTION_ENTRY_READONLY';
    const EXCEPTION_PROVIDER_NOT_EXIST = 'EXCEPTION_PROVIDER_NOT_EXIST';

    public function __construct(
        UserProviderInterface $userProvider, 
        RoleProviderInterface $roleProvider,
        AuditLog $logger = null) {
        if (empty($userProvider)) {
            throw new \Exception(UserAccess::EXCEPTION_PROVIDER_NOT_EXIST);
        }
        $this->userProvider = $userProvider;
        $this->inbuiltUserProvider = new StaticUserProvider();
        if ($roleProvider) {
            $this->roleProvider = $roleProvider;
        }
        $this->inbuiltRoleProvider = new StaticRoleProvider();

    }

    public function getUserProvider(): UserProviderInterface {
        return $this->userProvider;
    }

    public function getInbuiltUserProvider(): UserProviderInterface {
        return $this->inbuiltUserProvider;
    }

    public function getRoleProvider(): RoleProviderInterface {
        return $this->roleProvider;
        if ($this->roleProvider) {
            return $this->roleProvider;
        } else {
            throw new \Exception(UserAccess::EXCEPTION_PROVIDER_NOT_EXIST);
        }
    }

    public function getInbuiltRoleProvider(): RoleProviderInterface {
        return $this->inbuiltRoleProvider;
    }

}