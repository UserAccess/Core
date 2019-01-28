<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\Core\Entry\User;
use \UserAccess\Core\Util\Password;

class UserTest extends TestCase {

    public function test() {
        $user = new User('userid1');
        $this->assertNotEmpty($user);
        $this->assertEquals('userid1', $user->getId());
        $userAttributes = $user->getAttributes();
        $this->assertEquals('userid1', $userAttributes['id']);
        $user->setDisplayName('User 1');
        $this->assertEquals('User 1', $user->getDisplayName());
        $user->setEmail('userid1.test@test.com');
        $this->assertEquals('userid1.test@test.com', $user->getEmail());
        $user->setLocked(false);
        $this->assertFalse($user->isLocked());
        $user->setLocked(true);
        $this->assertTrue($user->isLocked());
        $user->addRole('Everyone');
        $this->assertTrue($user->hasRole('Everyone'));
        $user->addRole('Administrator');
        $user->addRole('Guests');
        $this->assertTrue($user->hasRole('Everyone'));
        $this->assertTrue($user->hasRole('Administrator'));
        $user->removeRole('Administrator');
        $this->assertTrue($user->hasRole('Everyone'));
        $this->assertFalse($user->hasRole('Administrator'));
        $user->setPasswordHash(Password::hash('password'));
        $this->assertTrue($user->authenticate('password'));
        $this->assertFalse($user->authenticate('wrong_password'));
        $user->setFailedLoginAttempts(5);
        $this->assertEquals(5, $user->getFailedLoginAttempts());
        $this->assertFalse($user->authenticate('wrong_password'));
        //print_r($user->getAttributes());

    }

}