<?php

namespace UserAccess;

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

    const EXCEPTION_INVALID_ID = 'EXCEPTION_INVALID_ID';
    const EXCEPTION_INVALID_EMAIL = 'EXCEPTION_INVALID_EMAIL';
    const EXCEPTION_INVALID_PASSWORD = 'EXCEPTION_INVALID_PASSWORD';
    const EXCEPTION_INVALID_VALUE = 'EXCEPTION_INVALID_VALUE';
    const EXCEPTION_DUPLICATE_EMAIL = 'EXCEPTION_DUPLICATE_EMAIL';
    const EXCEPTION_ENTRY_ALREADY_EXIST = 'EXCEPTION_ENTRY_ALREADY_EXIST';
    const EXCEPTION_ENTRY_NOT_EXIST = 'EXCEPTION_ENTRY_NOT_EXIST';
    const EXCEPTION_ENTRY_READONLY = 'EXCEPTION_ENTRY_READONLY';
    const EXCEPTION_PROVIDER_NOT_EXIST = 'EXCEPTION_PROVIDER_NOT_EXIST';
    const EXCEPTION_AUTHENTICATION_FAILED = 'EXCEPTION_AUTHENTICATION_FAILED';

    const COMPARISON_EQUAL = 'COMPARISON_EQUAL';
    const COMPARISON_LIKE = 'COMPARISON_LIKE';

    public function __construct(
        UserProviderInterface $userProvider = null, 
        RoleProviderInterface $roleProvider = null,
        AuditLog $logger = null) {
        $this->userProvider = $userProvider;
        $this->inbuiltUserProvider = new StaticUserProvider();
        $this->roleProvider = $roleProvider;
        $this->inbuiltRoleProvider = new StaticRoleProvider();
    }

    public function getUserProvider(): UserProviderInterface {
        if (empty($this->userProvider)) {
            throw new \Exception(UserAccess::EXCEPTION_PROVIDER_NOT_EXIST);
        }
        return $this->userProvider;
    }

    public function getInbuiltUserProvider(): UserProviderInterface {
        return $this->inbuiltUserProvider;
    }

    public function getRoleProvider(): RoleProviderInterface {
        if (empty($this->roleProvider)) {
            throw new \Exception(UserAccess::EXCEPTION_PROVIDER_NOT_EXIST);
        }
        return $this->roleProvider;
    }

    public function getInbuiltRoleProvider(): RoleProviderInterface {
        return $this->inbuiltRoleProvider;
    }

    public function isUserExisting(string $id): bool {
        if (!empty($this->userProvider) && $this->userProvider->isUserExisting($id)) {
            return true;
        } else if ($this->inbuiltUserProvider->isUserExisting($id)) {
            return true;
        } else {
            return false;
        }
    }

    public function getUser(string $id): UserInterface {
        if (!empty($this->userProvider) && $this->userProvider->isUserExisting($id)) {
            return $this->userProvider->getUser($id);
        } else if ($this->inbuiltUserProvider->isUserExisting($id)) {
            return $this->inbuiltUserProvider->getUser($id);
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function getUsers(): array {
        $entries;
        if (!empty($this->userProvider)) {
            $entries = array_merge($this->userProvider->getUsers(), $this->inbuiltUserProvider->getUsers());
        } else {
            $entries = $this->inbuiltUserProvider->getUsers();
        }
        ksort($entries);
        return $entries;
    }

    public function findUsers(string $attributeName, string $attributeValue, string $comparisonOperator): array {
        $entries;
        if (!empty($this->userProvider)) {
            $entries = array_merge($this->userProvider->findUsers($attributeName, $attributeValue, $comparisonOperator), 
                $this->inbuiltUserProvider->findUsers($attributeName, $attributeValue, $comparisonOperator));
        } else {
            $entries = $this->inbuiltUserProvider->findUsers($attributeName, $attributeValue, $comparisonOperator);
        }  
        ksort($entries);
        return $entries;
    }

    public function isRoleExisting(string $id): bool {
        if (!empty($this->roleProvider) && $this->roleProvider->isRoleExisting($id)) {
            return true;
        } else if ($this->inbuiltRoleProvider->isRoleExisting($id)) {
            return true;
        } else {
            return false;
        }
    }

    public function getRole(string $id): RoleInterface {
        if (!empty($this->roleProvider) && $this->roleProvider->isRoleExisting($id)) {
            return $this->roleProvider->getRole($id);
        } else if ($this->inbuiltRoleProvider->isRoleExisting($id)) {
            return $this->inbuiltRoleProvider->getRole($id);
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function getRoles(): array {
        $entries;
        if (!empty($this->roleProvider)) {
            $entries = array_merge($this->roleProvider->getRoles(), $this->inbuiltRoleProvider->getRoles());
        } else {
            $entries = $this->inbuiltRoleProvider->getRoles();
        }
        ksort($entries);
        return $entries;
    }

    public function findRoles(string $attributeName, string $attributeValue, string $comparisonOperator): array {
        $entries;
        if (!empty($this->roleProvider)) {
            $entries = array_merge($this->roleProvider->findRoles($attributeName, $attributeValue, $comparisonOperator), 
            $this->inbuiltRoleProvider->findRoles($attributeName, $attributeValue, $comparisonOperator));
        } else {
            $entries = $this->inbuiltRoleProvider->findRoles($attributeName, $attributeValue, $comparisonOperator);
        }
        ksort($entries);
        return $entries;
    }

}