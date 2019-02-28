<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\Core\Provider\RoleProviderInterface;
use \UserAccess\Core\Provider\FilebaseRoleProvider;
use \UserAccess\Core\Provider\StaticRoleProvider;
use \UserAccess\Core\Entry\Role;

class RoleProviderTest extends TestCase {

    public function test() {
        $this->performTest(new FilebaseRoleProvider('testdata/roles'));
        $this->performTest(new StaticRoleProvider());
    }

    public function performTest(RoleProviderInterface $provider) {
        if ($provider->isExisting('roleid1')) {
            $provider->deleteRole('roleid1');
        }
        if ($provider->isExisting('roleid2')) {
            $provider->deleteRole('roleid2');
        }
        $this->assertFalse($provider->isExisting('roleid1'));
        $this->assertFalse($provider->isExisting('roleid2'));

        $role1 = new Role('roleid1');
        $role1->setDisplayName('roleid1 test');
        $role1->setDescription('roleid1 test description');
        $role2 = new Role('roleid2');
        $role2->setDisplayName('roleid2 test');
        $role2->setDescription('roleid2 test description');
        $provider->createRole($role1);
        $provider->createRole($role2);

        $this->assertTrue($provider->isExisting('roleid1'));
        $this->assertTrue($provider->isExisting('roleid2'));
        $role_test1 = $provider->getRole('roleid1');
        $role_test2 = $provider->getRole('roleid2');
        $this->assertNotEmpty($role_test1);
        $this->assertNotEmpty($role_test2);
        $this->assertEquals($provider->isReadOnly(), $role_test1->isReadOnly());
        $this->assertEquals($provider->isReadOnly(), $role_test2->isReadOnly());

        $this->assertEquals('roleid1', $role_test1->getId());
        $this->assertEquals('roleid1 test description', $role_test1->getDescription());
        $this->assertEquals('roleid2', $role_test2->getId());
        $this->assertEquals('roleid2 test description', $role_test2->getDescription());

        $this->assertFalse($provider->isExisting('roleid3'));
        try {
            $provider->getrole('roleid3');
        } catch (\Exception $e) {
            $this->assertNotEmpty($e);
        }

        if (!$provider->isReadOnly()) {
            $role_test1 = $provider->getRole('roleid1');
            $role_test1->setDisplayName('roleid1 test update');
            $role_test1->setDescription('roleid1 test description update');
            $provider->updateRole($role_test1);
            $role_test1 = $provider->getRole('roleid1');
            $this->assertEquals('roleid1', $role_test1->getId());
            $this->assertEquals('roleid1 test description update', $role_test1->getDescription());
        }

        $roles = $provider->getAllroles();
        $this->assertNotEmpty($roles);
        $this->assertEquals(2, count($roles));

    }

}