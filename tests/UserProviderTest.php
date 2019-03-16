<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\Core\UserAccess;
use \UserAccess\Core\Provider\UserProviderInterface;
use \UserAccess\Core\Provider\FilebaseUserProvider;
use \UserAccess\Core\Provider\StaticUserProvider;
use \UserAccess\Core\Entry\User;
use \UserAccess\Core\Util\Password;

class UserProviderTest extends TestCase {

    public function test() {
        $this->performTest(new StaticUserProvider());
        $this->performTest(new FilebaseUserProvider('data/users'));
    }

    public function performTest(UserProviderInterface $provider) {
        if ($provider->isUserExisting('userid1')) {
            $provider->deleteUser('userid1');
        }
        if ($provider->isUserExisting('userid2')) {
            $provider->deleteUser('userid2');
        }
        $this->assertFalse($provider->isUserExisting('userid1'));
        $this->assertFalse($provider->isUserExisting('userid2'));

        $user1 = new User('userid1');
        $user1->setDisplayName('userid1 test');
        $user1->setPasswordHash(Password::hash('password1'));
        $user1->setEmail('userid1.test@test.com');
        $user1->setRoles(array('Everyone', 'Administrators'));
        $user2 = new User('userid2');
        $user2->setDisplayName('userid2 test');
        $user2->setPasswordHash(Password::hash('password2'));
        $user2->setEmail('userid2.test@test.com');
        $user2->addRole('Everyone');
        $user2->addRole('Administrators');
        $provider->createUser($user1);
        $provider->createUser($user2);

        $this->assertTrue($provider->isUserExisting('userid1'));
        $this->assertTrue($provider->isUserExisting('userid2'));
        $user_test1 = $provider->getUser('userid1');
        $user_test2 = $provider->getUser('userid2');
        $this->assertNotEmpty($user_test1);
        $this->assertNotEmpty($user_test2);
        $this->assertEquals($provider->isProviderReadOnly(), $user_test1->isReadOnly());
        $this->assertEquals($provider->isProviderReadOnly(), $user_test2->isReadOnly());

        $this->assertEquals('USERID1', $user_test1->getId());
        $this->assertEquals('userid1.test@test.com', $user_test1->getEmail());
        $this->assertTrue($user_test1->hasRole('Administrators'));
        $this->assertFalse($user_test1->hasRole('Guests'));
        $this->assertEquals('USERID2', $user_test2->getId());
        $this->assertEquals('userid2.test@test.com', $user_test2->getEmail());
        $this->assertTrue($user_test2->hasRole('Administrators'));
        $this->assertFalse($user_test2->hasRole('Guests'));
        $this->assertTrue($user_test1->authenticate('password1'));
        $this->assertTrue($user_test2->authenticate('password2'));

        $find = $provider->findUsers('displayName', 'userid1 TEST ', UserAccess::COMPARISON_EQUAL);
        $this->assertNotEmpty($find);
        $this->assertEquals(1, count($find));
        $find = $provider->findUsers('email', 'userid1.test@test.com', UserAccess::COMPARISON_EQUAL);
        $this->assertNotEmpty($find);
        $this->assertEquals(1, count($find));
        $find = $provider->findUsers('displayName', 'USERID', UserAccess::COMPARISON_LIKE);
        $this->assertNotEmpty($find);
        $this->assertEquals(2, count($find));

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
        $user_test1->removeRole('Administrators');
        if (!$provider->isProviderReadOnly()) {
            $provider->updateUser($user_test1);
            $user_test1 = $provider->getUser('userid1');
            $this->assertEquals('USERID1', $user_test1->getId());
            $this->assertEquals('userid1 test update', $user_test1->getDisplayName());
            $this->assertEquals('userid1.test_update@test.com', $user_test1->getEmail());
            $this->assertFalse($user_test1->authenticate('password1'));
            $this->assertTrue($user_test1->authenticate('password1_update'));
            $this->assertTrue($user_test1->hasRole('Everyone'));
            $this->assertFalse($user_test1->hasRole('Administrators'));
        }

        // delete attribute test
        $user_test1->setDisplayName('');
        if (!$provider->isProviderReadOnly()) {
            $provider->updateUser($user_test1);
            $user_test1 = $provider->getUser('userid1');
            $this->assertEquals('', $user_test1->getDisplayName());
            $user_test1->setDisplayName('userid1 test');
            $provider->updateUser($user_test1);
            $user_test1 = $provider->getUser('userid1');
            $this->assertEquals('userid1 test', $user_test1->getDisplayName());
            $user_test1->setAttributes(array('displayName' => ''));
            $provider->updateUser($user_test1);
            $user_test1 = $provider->getUser('userid1');
            $this->assertEquals('', $user_test1->getDisplayName());
        }

        $users = $provider->getUsers();
        $this->assertNotEmpty($users);
        $this->assertEquals(2, count($users));

    }

}