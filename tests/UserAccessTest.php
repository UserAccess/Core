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
        $roleProvider = new FilebaseRoleProvider('testdata/roles');
        $userAccess = new UserAccess($userProvider, $roleProvider);
        $this->assertNotEmpty($userAccess->getUserProvider());
        $this->assertNotEmpty($userAccess->getInbuiltUserProvider());
        $this->assertNotEmpty($userAccess->getRoleProvider());
        $this->assertNotEmpty($userAccess->getInbuiltUserProvider());

        $user = new User('administrator');
        $userAccess->getInbuiltUserProvider()->createUser($user);
        if ($userAccess->getUserProvider()->isUserExisting('userid1')) {
            $userAccess->getUserProvider()->deleteUser('userid1');
        }
        if ($userAccess->getUserProvider()->isUserExisting('userid_2')) {
            $userAccess->getUserProvider()->deleteUser('userid_2');
        }
        $user = new User('userid1');
        $user->setPassword('password');
        $userAccess->getUserProvider()->createUser($user);
        $this->assertTrue($userAccess->isUserExisting('administrator'));
        $this->assertTrue($userAccess->isUserExisting('userid1'));
        $users = $userAccess->getUsers();
        $this->assertNotEmpty($users);
        $this->assertEquals(2, count($users));
        $user = $userAccess->getUser('administrator');
        $this->assertNotEmpty($user);
        $this->assertEquals('administrator', $user->getId());
        $this->assertTrue($user->isReadOnly());
        $user = $userAccess->getUser('userid1');
        $this->assertNotEmpty($user);
        $this->assertEquals('userid1', $user->getId());
        $this->assertFalse($user->isReadOnly());
        //$this->assertTrue($userAccess->selfserviceLogin('userid1', 'password'));

        $role = new Role('administrators');
        $userAccess->getInbuiltRoleProvider()->createRole($role);
        if ($userAccess->getRoleProvider()->isRoleExisting('roleid1')) {
            $userAccess->getRoleProvider()->deleteRole('roleid1');
        }
        if ($userAccess->getRoleProvider()->isRoleExisting('roleid2')) {
            $userAccess->getRoleProvider()->deleteRole('roleid2');
        }
        $role = new Role('roleid1');
        $userAccess->getRoleProvider()->createRole($role);
        $this->assertTrue($userAccess->isRoleExisting('administrators'));
        $this->assertTrue($userAccess->isRoleExisting('roleid1'));
        $roles = $userAccess->getRoles();
        $this->assertNotEmpty($roles);
        $this->assertEquals(2, count($roles));
        $role = $userAccess->getRole('administrators');
        $this->assertNotEmpty($role);
        $this->assertEquals('administrators', $role->getId());
        $this->assertTrue($role->isReadOnly());
        $role = $userAccess->getRole('roleid1');
        $this->assertNotEmpty($role);
        $this->assertEquals('roleid1', $role->getId());
        $this->assertFalse($role->isReadOnly());

        $find = $userAccess->findUsers('id', 's', UserAccess::COMPARISON_LIKE);
        $this->assertNotEmpty($find);
        $this->assertEquals(2, count($find));

        $find = $userAccess->findRoles('id', 'r', UserAccess::COMPARISON_LIKE);
        $this->assertNotEmpty($find);
        $this->assertEquals(2, count($find));

    }

}