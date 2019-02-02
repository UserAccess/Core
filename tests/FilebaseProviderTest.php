<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\Core\Provider\FilebaseProvider;
use \UserAccess\Core\Entry\User;
use \UserAccess\Core\Util\Password;

class FilebaseProviderTest extends TestCase {

    public function test() {
        $provider = new FilebaseProvider('testdata');

        if ($provider->isUserExisting('userid1')) {
            $provider->deleteUser('userid1');
        }
        if ($provider->isUserExisting('userid2')) {
            $provider->deleteUser('userid2');
        }
        $this->assertFalse($provider->isUserExisting('userid1'));
        $this->assertFalse($provider->isUserExisting('userid2'));

        $user1 = new User('userid1');
        $user1->setDisplayName = 'userid1 test';
        $user1->setPasswordHash(Password::hash('password1'));
        $user1->setEmail('userid1.test@test.com');
        $user2 = new User('userid2');
        $user2->setDisplayName = 'userid2 test';
        $user2->setPasswordHash(Password::hash('password2'));
        $user2->setEmail('userid2.test@test.com');
        $provider->createUser($user1);
        $provider->createUser($user2);

        $this->assertTrue($provider->isUserExisting('userid1'));
        $this->assertTrue($provider->isUserExisting('userid2'));
        $user_test1 = $provider->getUser('userid1');
        $user_test2 = $provider->getUser('userid2');
        $this->assertNotEmpty($user_test1);
        $this->assertNotEmpty($user_test2);

        $this->assertEquals('userid1', $user_test1->getId());
        $this->assertEquals('userid1.test@test.com', $user_test1->getEmail());
        $this->assertEquals('userid2', $user_test2->getId());
        $this->assertEquals('userid2.test@test.com', $user_test2->getEmail());
        $this->assertTrue($user_test1->authenticate('password1'));
        $this->assertTrue($user_test2->authenticate('password2'));

        $this->assertFalse($provider->isUserExisting('userid3'));
        try {
            $provider->getUser('userid3');
        } catch (\Exception $e) {
            $this->assertNotEmpty($e);
        }

        $user_test1 = $provider->getUser('userid1');
        $user_test1->setDisplayName('userid1 test update');
        $user_test1->setPasswordHash(Password::hash('password1_update'));
        $user_test1->setEmail('userid1.test_update@test.com');
        $provider->updateUser($user_test1);
        $user_test1 = $provider->getUser('userid1');
        $this->assertEquals('userid1', $user_test1->getId());
        $this->assertEquals('userid1 test update', $user_test1->getDisplayName());
        $this->assertFalse($user_test1->authenticate('password1'));
        $this->assertTrue($user_test1->authenticate('password1_update'));
        $this->assertEquals('userid1 test update', $user_test1->getDisplayName());
        $this->assertEquals('userid1.test_update@test.com', $user_test1->getEmail());

        $users = $provider->getAllUsers();
        $this->assertNotEmpty($users);
        $this->assertEquals(2, count($users));

    }

}