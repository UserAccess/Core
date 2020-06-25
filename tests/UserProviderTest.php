<?php

use \PHPUnit\Framework\TestCase;

use \PragmaPHP\UserAccess\UserAccess;
use \PragmaPHP\UserAccess\Provider\UserProviderInterface;
use \PragmaPHP\UserAccess\Provider\FileUserProvider;
use \PragmaPHP\UserAccess\Provider\StaticUserProvider;
use \PragmaPHP\UserAccess\Entry\User;

class UserProviderTest extends TestCase {

    public function test() {
        $this->performTest(new StaticUserProvider());

        $userProvider = new FileUserProvider('testdata/users');
        $userProvider->deleteUsers();
        $this->performTest(new FileUserProvider('testdata/users'));
    }

    public function performTest(UserProviderInterface $provider) {
        if ($provider->isUniqueNameExisting('userid1')) {
            $provider->deleteUser('userid1');
        }
        if ($provider->isUniqueNameExisting('USERID_2')) {
            $provider->deleteUser('USERID_2');
        }
        $this->assertFalse($provider->isUniqueNameExisting('userid1'));
        $this->assertFalse($provider->isUniqueNameExisting('USERID_2'));

        $user1 = new User('userid1');
        $user1->setDisplayName('userid1 test');
        $user1->setGivenName('Userid1');
        $user1->setFamilyName('Test');
        $user1->setPassword('password1');
        $user1->setEmail('userid1.test@test.com');
        $user1->setRoles(array('Everyone', 'Administrators'));
        $user2 = new User('USERID_2');
        $user2->setDisplayName('USERID_2 test');
        $user2->setGivenName('Userid2');
        $user2->setFamilyName('Test');
        $user2->setPassword('password2');
        $user2->setEmail('userid_2.test@test.com');
        $user2->addRole('Everyone');
        $user2->addRole('Administrators');
        $user1 = $provider->createUser($user1);
        $user2 = $provider->createUser($user2);

        $user3 = new User('user');
        $user3->setDisplayName('user test');
        $user3->setPassword('password1');
        $user3->setEmail('user.test@test.com');
        $user3->setRoles(array('Everyone', 'Administrators'));
        $user3 = $provider->createUser($user3);

        $user4 = new User('userid4');
        $user3->setEmail('user.test@test.com');
        $user3 = $provider->createUser($user4);

        $this->assertTrue($provider->isUniqueNameExisting('userid1'));
        $this->assertTrue($provider->isUniqueNameExisting('USERID_2'));
        $user_test1 = $provider->getUser($user1->getId());
        $user_test2 = $provider->getUser($user2->getId());
        $this->assertNotEmpty($user_test1);
        $this->assertNotEmpty($user_test2);
        $this->assertEquals($provider->isReadOnly(), $user_test1->isReadOnly());
        $this->assertEquals($provider->isReadOnly(), $user_test2->isReadOnly());

        $this->assertEquals('userid1', $user_test1->getUniqueName());
        $this->assertEquals('userid1', $user_test1->getUserName());
        $this->assertEquals('Userid1', $user_test1->getGivenName());
        $this->assertEquals('Test', $user_test1->getFamilyName());
        $this->assertEquals('userid1.test@test.com', $user_test1->getEmail());
        $this->assertEquals(['userid1.test@test.com'], $user_test1->getEmails());
        $this->assertTrue($user_test1->hasRole('Administrators'));
        $this->assertFalse($user_test1->hasRole('Guests'));
        $this->assertEquals('userid_2', $user_test2->getUniqueName());
        $this->assertEquals('userid_2', $user_test2->getUserName());
        $this->assertEquals('Userid2', $user_test2->getGivenName());
        $this->assertEquals('Test', $user_test2->getFamilyName());
        $this->assertEquals('userid_2.test@test.com', $user_test2->getEmail());
        $this->assertEquals(['userid_2.test@test.com'], $user_test2->getEmails());
        $this->assertTrue($user_test2->hasRole('Administrators'));
        $this->assertFalse($user_test2->hasRole('Guests'));
        $this->assertTrue($user_test1->verifyPassword('password1'));
        $this->assertTrue($user_test2->verifyPassword('password2'));

        $find = $provider->findUsers('displayName', 'userid1 TEST ');
        $this->assertNotEmpty($find);
        $this->assertEquals(1, count($find));
        $find = $provider->findUsers('email', 'userid1.test@test.com');
        $this->assertNotEmpty($find);
        $this->assertEquals(1, count($find));
        $find = $provider->findUsers('displayName', '*USERID*');
        $this->assertNotEmpty($find);
        $this->assertEquals(2, count($find));

        $this->assertFalse($provider->isUniqueNameExisting('userid3'));
        try {
            $provider->getUser('userid3');
        } catch (\Exception $e) {
            $this->assertNotEmpty($e);
        }

        $user_test1 = $provider->getUser($user1->getId());
        $user_test1->setDisplayName('userid1 test update');
        $user_test1->setGivenName('Userid1 update');
        $user_test1->setFamilyName('Test update');
        $user_test1->setPassword('password1_update');
        $user_test1->setEmails(['userid1.test_update@test.com']);
        $user_test1->removeRole('Administrators');
        if (!$provider->isReadOnly()) {
            $provider->updateUser($user_test1);
            $user_test1 = $provider->getUser($user1->getId());
            $this->assertEquals('userid1', $user_test1->getUniqueName());
            $this->assertEquals('userid1', $user_test1->getUserName());
            $this->assertEquals('userid1 test update', $user_test1->getDisplayName());
            $this->assertEquals('Userid1 update', $user_test1->getGivenName());
            $this->assertEquals('Test update', $user_test1->getFamilyName());
            $this->assertEquals('userid1.test_update@test.com', $user_test1->getEmail());
            $this->assertFalse($user_test1->verifyPassword('password1'));
            $this->assertTrue($user_test1->verifyPassword('password1_update'));
            $this->assertTrue($user_test1->hasRole('Everyone'));
            $this->assertFalse($user_test1->hasRole('Administrators'));
        }

        // delete attribute test
        $user_test1->setDisplayName('');
        if (!$provider->isReadOnly()) {
            $provider->updateUser($user_test1);
            $user_test1 = $provider->getUser($user_test1->getId());
            $this->assertEquals('', $user_test1->getDisplayName());
            $user_test1->setDisplayName('userid1 test');
            $provider->updateUser($user_test1);
            $user_test1 = $provider->getUser($user_test1->getId());
            $this->assertEquals('userid1 test', $user_test1->getDisplayName());
            $user_test1->setAttributes(array('displayName' => ''));
            $provider->updateUser($user_test1);
            $user_test1 = $provider->getUser($user_test1->getId());
            $this->assertEquals('', $user_test1->getDisplayName());
        }

        $users = $provider->getUsers();
        $this->assertNotEmpty($users);
        $this->assertEquals(4, count($users));

        if (!$provider->isReadOnly()) {
            $provider->deleteUsers();
        }

    }

}