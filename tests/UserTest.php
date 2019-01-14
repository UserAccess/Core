<?php

use PHPUnit\Framework\TestCase;
use UserAccess\Core\User;

class UserTest extends TestCase
{

    public function test() {
        $user = new User('userid');
        $this->assertNotEmpty($user);
        $user->setPassword('password');
        $this->assertTrue($user->verifyPassword('password'));
        $this->assertFalse($user->verifyPassword('wrong_password'));
    }

}