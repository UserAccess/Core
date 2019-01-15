<?php

use PHPUnit\Framework\TestCase;
use UserAccess\Core\User;
use UserAccess\Core\Password;

class UserTest extends TestCase
{

    public function test() {
        $user = new User('userid1');
        $this->assertNotEmpty($user);
        $this->assertEquals('userid1', $user->getUserId());
        $userAttributes = $user->getAttributes();
        $this->assertEquals('userid1', $userAttributes['userId']);

        $user->setPassword('password');
        $this->assertTrue($user->verifyPassword('password'));
        $this->assertFalse($user->verifyPassword('wrong_password'));

        $user = new User('userid2');
        $this->assertNotEmpty($user);

        $user->setPasswordHash(Password::hash('password'));
        $this->assertTrue($user->verifyPassword('password'));
        $this->assertFalse($user->verifyPassword('wrong_password'));
    }

}