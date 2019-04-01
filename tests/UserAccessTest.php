<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\UserAccess;
use \UserAccess\Auth\SessionAuthenticator;
use \UserAccess\Entry\User;
use \UserAccess\Entry\Role;
use \UserAccess\Provider\FilebaseUserProvider;
use \UserAccess\Provider\FilebaseRoleProvider;
use \UserAccess\Util\AuditLog;

class UserAccessTest extends TestCase {

    public function test() {
        $userProvider = new FilebaseUserProvider('testdata/users');
        $userProvider->deleteUsers();
        $roleProvider = new FilebaseRoleProvider('testdata/roles');
        $roleProvider->deleteRoles();
        $userAccess = new UserAccess($userProvider, $roleProvider);
        $this->assertNotEmpty($userAccess->getUserProvider());
        $this->assertNotEmpty($userAccess->getRoleProvider());

        $user = new User('userid1');
        $user->setPassword('password');
        $user = $userAccess->getUserProvider()->createUser($user);
        $this->assertTrue($userAccess->getUserProvider()->isUniqueNameExisting('userid1'));
        $users = $userAccess->getUserProvider()->getUsers();
        $this->assertNotEmpty($users);
        $this->assertEquals(1, count($users));
        $user = $userAccess->getUserProvider()->getUser($user->getId());
        $this->assertNotEmpty($user);
        $this->assertEquals('userid1', $user->getUniqueName());
        $this->assertFalse($user->isReadOnly());

        $role = new Role('roleid1');
        $role = $userAccess->getRoleProvider()->createRole($role);
        $this->assertTrue($userAccess->getRoleProvider()->isUniqueNameExisting('roleid1'));
        $roles = $userAccess->getRoleProvider()->getRoles();
        $this->assertNotEmpty($roles);
        $this->assertEquals(1, count($roles));
        $role = $userAccess->getRoleProvider()->getRole($role->getId());
        $this->assertNotEmpty($role);
        $this->assertEquals('roleid1', $role->getUniqueName());
        $this->assertFalse($role->isReadOnly());

        $find = $userAccess->getUserProvider()->findUsers('uniqueName', 's', UserAccess::COMPARISON_LIKE);
        $this->assertNotEmpty($find);
        $this->assertEquals(1, count($find));

        $find = $userAccess->getRoleProvider()->findRoles('uniqueName', 'r', UserAccess::COMPARISON_LIKE);
        $this->assertNotEmpty($find);
        $this->assertEquals(1, count($find));

        $userAccess->getRoleProvider()->deleteRoles();
        $userAccess->getUserProvider()->deleteUsers();

    }

}