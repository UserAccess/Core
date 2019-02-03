<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\Core\UserAccess;
use \UserAccess\Core\Provider\StaticUserProvider;

class UserAccessTest extends TestCase {

    public function test() {
        $provider = new StaticUserProvider();
        $userAccess = new UserAccess($provider);
        $this->assertNotEmpty($userAccess->getUserProvider());
    }

}