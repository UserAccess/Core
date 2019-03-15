<?php

namespace UserAccess\Core;

use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Entry\RoleInterface;
use \UserAccess\Core\Provider\UserProviderInterface;
use \UserAccess\Core\Provider\StaticUserProvider;
use \UserAccess\Core\Provider\RoleProviderInterface;
use \UserAccess\Core\Provider\StaticRoleProvider;
use \UserAccess\Core\Util\AuditLog;

class UserAccess {

    private $userProvider;
    private $inbuiltUserProvider;
    private $roleProvider;
    private $inbuiltRoleProvider;

    const EXCEPTION_MISSING_ID = 'EXCEPTION_MISSING_ID';
    const EXCEPTION_INVALID_ID = 'EXCEPTION_INVALID_ID';
    const EXCEPTION_INVALID_EMAIL = 'EXCEPTION_INVALID_EMAIL';
    const EXCEPTION_ENTRY_ALREADY_EXIST = 'EXCEPTION_ENTRY_ALREADY_EXIST';
    const EXCEPTION_ENTRY_NOT_EXIST = 'EXCEPTION_ENTRY_NOT_EXIST';
    const EXCEPTION_ENTRY_READONLY = 'EXCEPTION_ENTRY_READONLY';
    const EXCEPTION_PROVIDER_NOT_EXIST = 'EXCEPTION_PROVIDER_NOT_EXIST';

    const COMPARISON_EQUAL = 'COMPARISON_EQUAL';
    const COMPARISON_LIKE = 'COMPARISON_LIKE';

    public function __construct(
        UserProviderInterface $userProvider, 
        RoleProviderInterface $roleProvider,
        AuditLog $logger = null) {
        if (empty($userProvider) || empty($roleProvider)) {
            throw new \Exception(UserAccess::EXCEPTION_PROVIDER_NOT_EXIST);
        }
        $this->userProvider = $userProvider;
        $this->inbuiltUserProvider = new StaticUserProvider();
        $this->roleProvider = $roleProvider;
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
    }

    public function getInbuiltRoleProvider(): RoleProviderInterface {
        return $this->inbuiltRoleProvider;
    }

    public function isUserExisting(string $id): bool {
        if ($this->userProvider->isUserExisting($id)) {
            return true;
        } else if ($this->inbuiltUserProvider->isUserExisting($id)) {
            return true;
        } else {
            return false;
        }
    }

    public function getUser(string $id): UserInterface {
        if ($this->userProvider->isUserExisting($id)) {
            return $this->userProvider->getUser($id);
        } else if ($this->inbuiltUserProvider->isUserExisting($id)) {
            return $this->inbuiltUserProvider->getUser($id);
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function getUsers(): array {
        return array_merge($this->userProvider->getUsers(), $this->inbuiltUserProvider->getUsers());
    }

    public function isRoleExisting(string $id): bool {
        if ($this->roleProvider->isRoleExisting($id)) {
            return true;
        } else if ($this->inbuiltRoleProvider->isRoleExisting($id)) {
            return true;
        } else {
            return false;
        }
    }

    public function getRole(string $id): RoleInterface {
        if ($this->roleProvider->isRoleExisting($id)) {
            return $this->roleProvider->getRole($id);
        } else if ($this->inbuiltRoleProvider->isRoleExisting($id)) {
            return $this->inbuiltRoleProvider->getRole($id);
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function getRoles(): array {
        return array_merge($this->roleProvider->getRoles(), $this->inbuiltRoleProvider->getRoles());
    }

}