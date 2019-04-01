<?php

namespace UserAccess;

use \UserAccess\Auth\AuthenticatorInterface;
use \UserAccess\Entry\UserInterface;
use \UserAccess\Entry\RoleInterface;
use \UserAccess\Provider\UserProviderInterface;
use \UserAccess\Provider\StaticUserProvider;
use \UserAccess\Provider\RoleProviderInterface;
use \UserAccess\Provider\StaticRoleProvider;
use \UserAccess\Util\AuditLog;

class UserAccess {

    private $userProvider;
    private $inbuiltUserProvider;
    private $roleProvider;
    private $inbuiltRoleProvider;
    private $authenticator;
    private $logger;

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
    const COMPARISON_LIKE = 'COMPARISON_LIKE';

    const SESSION_USERACCESS_USERID = 'SESSION_USERACCESS_USERID';
    const SESSION_USERACCESS_AUTHENTICATED = 'SESSION_USERACCESS_AUTHENTICATED';

    public function __construct(
        UserProviderInterface $userProvider = null, 
        RoleProviderInterface $roleProvider = null,
        AuditLog $logger = null) {

        $this->userProvider = $userProvider;
        $this->roleProvider = $roleProvider;
        $this->authenticator = $authenticator;
        $this->logger = $logger;
    }

    public function getUserProvider(): UserProviderInterface {
        return $this->userProvider;
    }

    public function getRoleProvider(): RoleProviderInterface {
        return $this->roleProvider;
    }

    public function getLogger(): AuditLog {
        return $this->logger;
    }

}