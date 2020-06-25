<?php

use \PHPUnit\Framework\TestCase;

use \PragmaPHP\UserAccess\Util\Password;

class PasswordTest extends TestCase {

    public function test() {
    	$passwordHash = Password::hash('password');
    	$wrongPasswordHash = Password::hash('wrongPassword');
    	$this->assertNotEmpty($passwordHash);
    	$this->assertNotEmpty($wrongPasswordHash);
        $this->assertTrue(Password::verify('password', $passwordHash));
        $this->assertFalse(Password::verify('password', $wrongPasswordHash));
    }

}