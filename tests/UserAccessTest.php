<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\UserAccess;
use \UserAccess\Entry\User;
use \UserAccess\Entry\Group;
use \UserAccess\Entry\Role;
use \UserAccess\Provider\FilebaseUserProvider;
use \UserAccess\Provider\FilebaseGroupProvider;
use \UserAccess\Provider\FilebaseRoleProvider;
use \UserAccess\Util\AuditLog;

class UserAccessTest extends TestCase {

    public function test() {
        $userProvider = new FilebaseUserProvider('testdata/users');
        $userProvider->deleteUsers();
        $groupProvider = new FilebaseGroupProvider('testdata/groups');
        $groupProvider->deleteGroups();
        $roleProvider = new FilebaseRoleProvider('testdata/roles');
        $roleProvider->deleteRoles();
        $userAccess = new UserAccess($userProvider, $groupProvider, $roleProvider);
        $this->assertNotEmpty($userAccess->getUserProvider());
        $this->assertNotEmpty($userAccess->getGroupProvider());
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

        $find = $userAccess->getUserProvider()->findUsers('uniqueName', 's', UserAccess::COMPARISON_LIKE);
        $this->assertNotEmpty($find);
        $this->assertEquals(1, count($find));

        $group = new Group('groupid1');
        $group = $userAccess->getGroupProvider()->createGroup($group);
        $this->assertTrue($userAccess->getGroupProvider()->isUniqueNameExisting('groupid1'));
        $groups = $userAccess->getGroupProvider()->getGroups();
        $this->assertNotEmpty($groups);
        $this->assertEquals(1, count($groups));
        $group = $userAccess->getGroupProvider()->getGroup($group->getId());
        $this->assertNotEmpty($group);
        $this->assertEquals('groupid1', $group->getUniqueName());
        $this->assertFalse($group->isReadOnly());

        $find = $userAccess->getGroupProvider()->findGroups('uniqueName', 'r', UserAccess::COMPARISON_LIKE);
        $this->assertNotEmpty($find);
        $this->assertEquals(1, count($find));

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

        $find = $userAccess->getRoleProvider()->findRoles('uniqueName', 'r', UserAccess::COMPARISON_LIKE);
        $this->assertNotEmpty($find);
        $this->assertEquals(1, count($find));

        $userAccess->getUserProvider()->deleteUsers();
        $userAccess->getGroupProvider()->deleteGroups();
        $userAccess->getRoleProvider()->deleteRoles();

    }

}