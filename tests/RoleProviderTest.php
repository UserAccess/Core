<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\UserAccess;
use \UserAccess\Provider\RoleProviderInterface;
use \UserAccess\Provider\FilebaseRoleProvider;
use \UserAccess\Provider\StaticRoleProvider;
use \UserAccess\Entry\Role;

class RoleProviderTest extends TestCase {

    public function test() {
        $this->performTest(new StaticRoleProvider());

        $roleProvider = new FilebaseRoleProvider('testdata/roles');
        $roleProvider->deleteRoles();
        $this->performTest($roleProvider);
    }

    public function performTest(RoleProviderInterface $provider) {
        if ($provider->isUniqueNameExisting('roleid1')) {
            $provider->deleteRole('roleid1');
        }
        if ($provider->isUniqueNameExisting('roleid2')) {
            $provider->deleteRole('roleid2');
        }
        $this->assertFalse($provider->isUniqueNameExisting('roleid1'));
        $this->assertFalse($provider->isUniqueNameExisting('roleid2'));

        $role1 = new Role('roleid1');
        $role1->setDisplayName('roleid1 test');
        $role1->setDescription('roleid1 test description');
        $role2 = new Role('roleid2');
        $role2->setDisplayName('roleid2 test');
        $role2->setDescription('roleid2 test description');
        $role1 = $provider->createRole($role1);
        $role2 = $provider->createRole($role2);

        $this->assertTrue($provider->isUniqueNameExisting('roleid1'));
        $this->assertTrue($provider->isUniqueNameExisting('roleid2'));
        $role_test1 = $provider->getRole($role1->getId());
        $role_test2 = $provider->getRole($role2->getId());
        $this->assertNotEmpty($role_test1);
        $this->assertNotEmpty($role_test2);
        $this->assertEquals($provider->isReadOnly(), $role_test1->isReadOnly());
        $this->assertEquals($provider->isReadOnly(), $role_test2->isReadOnly());

        $this->assertEquals('roleid1', $role_test1->getUniqueName());
        $this->assertEquals('roleid1 test', $role_test1->getDisplayName());
        $this->assertEquals('roleid1 test description', $role_test1->getDescription());
        $this->assertEquals('roleid2', $role_test2->getUniqueName());
        $this->assertEquals('roleid2 test', $role_test2->getDisplayName());
        $this->assertEquals('roleid2 test description', $role_test2->getDescription());

        $find = $provider->findRoles('displayName', 'roleid1 TEST ', UserAccess::COMPARISON_EQUAL);
        $this->assertNotEmpty($find);
        $this->assertEquals(1, count($find));
        $find = $provider->findRoles('description', 'roleid2 test description', UserAccess::COMPARISON_EQUAL);
        $this->assertNotEmpty($find);
        $this->assertEquals(1, count($find));
        $find = $provider->findRoles('displayName', 'ROLEID', UserAccess::COMPARISON_LIKE);
        $this->assertNotEmpty($find);
        $this->assertEquals(2, count($find));

        $this->assertFalse($provider->isUniqueNameExisting('roleid3'));
        try {
            $provider->getrole('roleid3');
        } catch (\Exception $e) {
            $this->assertNotEmpty($e);
        }

        if (!$provider->isReadOnly()) {
            $role_test1 = $provider->getRole($role1->getId());
            $role_test1->setDisplayName('roleid1 test update');
            $role_test1->setDescription('roleid1 test description update');
            $provider->updateRole($role_test1);
            $role_test1 = $provider->getRole($role1->getId());
            $this->assertEquals('roleid1', $role_test1->getUniqueName());
            $this->assertEquals('roleid1 test description update', $role_test1->getDescription());
        }

        $roles = $provider->getroles();
        $this->assertNotEmpty($roles);
        $this->assertEquals(2, count($roles));
        
        if (!$provider->isReadOnly()) {
            $provider->deleteRoles();
        }

    }

}