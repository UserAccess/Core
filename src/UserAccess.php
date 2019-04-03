<?php

namespace UserAccess;

use \UserAccess\Entry\UserInterface;
use \UserAccess\Entry\RoleInterface;
use \UserAccess\Provider\UserProviderInterface;
use \UserAccess\Provider\StaticUserProvider;
use \UserAccess\Provider\GroupProviderInterface;
use \UserAccess\Provider\StaticGroupProvider;
use \UserAccess\Provider\RoleProviderInterface;
use \UserAccess\Provider\StaticRoleProvider;
use \UserAccess\Util\AuditLog;

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

    const COMPARISON_EQUAL = 'COMPARISON_EQUAL';
    const COMPARISON_EQUAL_IGNORE_CASE = 'COMPARISON_EQUAL_IGNORE_CASE';
    const COMPARISON_LIKE = 'COMPARISON_LIKE';

    const SESSION_USERACCESS_USERID = 'SESSION_USERACCESS_USERID';
    const SESSION_USERACCESS_AUTHENTICATED = 'SESSION_USERACCESS_AUTHENTICATED';

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