<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\UserAccess;
use \UserAccess\Provider\GroupProviderInterface;
use \UserAccess\Provider\FileGroupProvider;
use \UserAccess\Provider\StaticGroupProvider;
use \UserAccess\Entry\Group;

class GroupProviderTest extends TestCase {

    public function test() {
        $this->performTest(new StaticGroupProvider());

        $groupProvider = new FileGroupProvider('testdata/groups');
        $groupProvider->deleteGroups();
        $this->performTest($groupProvider);
    }

    public function performTest(GroupProviderInterface $provider) {
        if ($provider->isUniqueNameExisting('groupid1')) {
            $provider->deleteGroup('groupid1');
        }
        if ($provider->isUniqueNameExisting('groupid2')) {
            $provider->deleteGroup('groupid2');
        }
        $this->assertFalse($provider->isUniqueNameExisting('groupid1'));
        $this->assertFalse($provider->isUniqueNameExisting('groupid2'));

        $group1 = new Group('groupid1');
        $group1->setDisplayName('groupid1 test');
        $group1->setDescription('groupid1 test description');
        $group2 = new Group('groupid2');
        $group2->setDisplayName('groupid2 test');
        $group2->setDescription('groupid2 test description');
        $group1 = $provider->createGroup($group1);
        $group2 = $provider->createGroup($group2);

        $this->assertTrue($provider->isUniqueNameExisting('groupid1'));
        $this->assertTrue($provider->isUniqueNameExisting('groupid2'));
        $group_test1 = $provider->getGroup($group1->getId());
        $group_test2 = $provider->getGroup($group2->getId());
        $this->assertNotEmpty($group_test1);
        $this->assertNotEmpty($group_test2);
        $this->assertEquals($provider->isReadOnly(), $group_test1->isReadOnly());
        $this->assertEquals($provider->isReadOnly(), $group_test2->isReadOnly());

        $this->assertEquals('groupid1', $group_test1->getUniqueName());
        $this->assertEquals('groupid1 test', $group_test1->getDisplayName());
        $this->assertEquals('groupid1 test description', $group_test1->getDescription());
        $this->assertEquals('groupid2', $group_test2->getUniqueName());
        $this->assertEquals('groupid2 test', $group_test2->getDisplayName());
        $this->assertEquals('groupid2 test description', $group_test2->getDescription());

        $find = $provider->findGroups('displayName', 'groupid1 TEST ');
        $this->assertNotEmpty($find);
        $this->assertEquals(1, count($find));
        $find = $provider->findGroups('description', 'groupid2 test description');
        $this->assertNotEmpty($find);
        $this->assertEquals(1, count($find));
        $find = $provider->findGroups('displayName', '*GROUPID*');
        $this->assertNotEmpty($find);
        $this->assertEquals(2, count($find));

        $this->assertFalse($provider->isUniqueNameExisting('groupid3'));
        try {
            $provider->getgroup('groupid3');
        } catch (\Exception $e) {
            $this->assertNotEmpty($e);
        }

        if (!$provider->isReadOnly()) {
            $group_test1 = $provider->getGroup($group1->getId());
            $group_test1->setDisplayName('groupid1 test update');
            $group_test1->setDescription('groupid1 test description update');
            $provider->updateGroup($group_test1);
            $group_test1 = $provider->getGroup($group1->getId());
            $this->assertEquals('groupid1', $group_test1->getUniqueName());
            $this->assertEquals('groupid1 test description update', $group_test1->getDescription());
        }

        $groups = $provider->getgroups();
        $this->assertNotEmpty($groups);
        $this->assertEquals(2, count($groups));
        
        if (!$provider->isReadOnly()) {
            $provider->deleteGroups();
        }

    }

}