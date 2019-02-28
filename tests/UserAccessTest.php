<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\Core\UserAccess;
use \UserAccess\Core\Provider\FilebaseUserProvider;
use \UserAccess\Core\Provider\FilebaseRoleProvider;

class UserAccessTest extends TestCase {

    public function test() {
        $userProvider = new FilebaseUserProvider('testdata/users');
        $roleProvider = new FilebaseRoleProvider('testdata/roles');
        $userAccess = new UserAccess($userProvider, $roleProvider);
        $this->assertNotEmpty($userAccess->getUserProvider());
        $this->assertNotEmpty($userAccess->getInbuiltUserProvider());
        $this->assertNotEmpty($userAccess->getRoleProvider());
        $this->assertNotEmpty($userAccess->getInbuiltUserProvider());

    }

}