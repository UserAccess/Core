<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\Core\UserAccess;
use \UserAccess\Core\Provider\FilebaseUserProvider;
use \UserAccess\Core\Provider\FilebaseRoleProvider;
use \UserAccess\Core\Provider\StaticUserProvider;

class UserAccessTest extends TestCase {

    public function test() {
        $userProvider = new FilebaseUserProvider('testdata/users');
        $fallbackUserProvider = new StaticUserProvider();
        $roleProvider = new FilebaseRoleProvider('testdata/roles');
        $userAccess = new UserAccess($userProvider, $fallbackUserProvider, $roleProvider);
        $this->assertNotEmpty($userAccess->getUserProvider());
        $this->assertNotEmpty($userAccess->getFallbackUserProvider());
        $this->assertNotEmpty($userAccess->getRoleProvider());
    }

}