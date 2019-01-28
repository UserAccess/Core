<?php

use PHPUnit\Framework\TestCase;

use UserAccess\Core\UserAccess;
use UserAccess\Core\Provider\StaticProvider;

class UserAccessTest extends TestCase {

    public function test() {
        $provider = new StaticProvider();
        $userAccess = new UserAccess($provider);
        $this->assertNotEmpty($userAccess->getProvider());
    }

}