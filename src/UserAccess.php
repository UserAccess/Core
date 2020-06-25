<?php

namespace PragmaPHP\UserAccess;

use \PragmaPHP\UserAccess\Entry\UserInterface;
use \PragmaPHP\UserAccess\Entry\RoleInterface;
use \PragmaPHP\UserAccess\Provider\UserProviderInterface;
use \PragmaPHP\UserAccess\Provider\StaticUserProvider;
use \PragmaPHP\UserAccess\Provider\GroupProviderInterface;
use \PragmaPHP\UserAccess\Provider\StaticGroupProvider;
use \PragmaPHP\UserAccess\Provider\RoleProviderInterface;
use \PragmaPHP\UserAccess\Provider\StaticRoleProvider;
use \PragmaPHP\UserAccess\Util\AuditLog;

class UserAccess {

    const EXCEPTION_INVALID_ID = 'EXCEPTION_INVALID_ID';
    const EXCEPTION_INVALID_UNIQUE_NAME = 'EXCEPTION_INVALID_UNIQUE_NAME';
    const EXCEPTION_INVALID_EMAIL = 'EXCEPTION_INVALID_EMAIL';
    const EXCEPTION_INVALID_PASSWORD = 'EXCEPTION_INVALID_PASSWORD';
    const EXCEPTION_INVALID_VALUE = 'EXCEPTION_INVALID_VALUE';
    const EXCEPTION_DUPLICATE_EMAIL = 'EXCEPTION_DUPLICATE_EMAIL';
    const EXCEPTION_ENTRY_ALREADY_EXIST = 'EXCEPTION_ENTRY_ALREADY_EXIST';
    const EXCEPTION_ENTRY_NOT_EXIST = 'EXCEPTION_ENTRY_NOT_EXIST';
    const EXCEPTION_ENTRY_READONLY = 'EXCEPTION_ENTRY_READONLY';
    const EXCEPTION_AUTHENTICATION_FAILED = 'EXCEPTION_AUTHENTICATION_FAILED';

    private $userProvider;
    private $groupProvider;
    private $roleProvider;
    private $logger;

    public function __construct(
        UserProviderInterface $userProvider = null,
        GroupProviderInterface $groupProvider = null,
        RoleProviderInterface $roleProvider = null,
        AuditLog $logger = null) {

        $this->userProvider = $userProvider;
        $this->groupProvider = $groupProvider;
        $this->roleProvider = $roleProvider;
        $this->logger = $logger;
    }

    public function getUserProvider(): UserProviderInterface {
        return $this->userProvider;
    }

    public function getGroupProvider(): GroupProviderInterface {
        return $this->groupProvider;
    }

    public function getRoleProvider(): RoleProviderInterface {
        return $this->roleProvider;
    }

    public function getLogger(): AuditLog {
        return $this->logger;
    }

}