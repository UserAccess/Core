<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\UserAccess;
use \UserAccess\Entry\UserInterface;
use \UserAccess\Entry\GroupInterface;
use \UserAccess\Entry\RoleInterface;
use \UserAccess\Provider\FilebaseUserProvider;
use \UserAccess\Provider\FilebaseGroupProvider;
use \UserAccess\Provider\FilebaseRoleProvider;
use \UserAccess\Rest\RestApp;
use \Slim\Http\Environment;
use \Slim\Http\Request;

class RestAppTest extends TestCase {

    private $app;

    private $userName = 'restu1';
    private $userId = '';
    private $groupName = 'restg1';
    private $groupId = '';
    private $roleName = 'restg1';
    private $roleId = '';

    public function setUp() {
        $userProvider = new FilebaseUserProvider('testdata/users');
        $groupProvider = new FilebaseGroupProvider('testdata/groups');
        $roleProvider = new FilebaseRoleProvider('testdata/roles');
        $userAccess = new UserAccess($userProvider, $groupProvider, $roleProvider);
        $this->app = new RestApp($userAccess);
    }

    public function test110_CreateUser() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/Users'
        ]);
        $req = Request::createFromEnvironment($env);
        $attributes = array();
        $attributes['userName'] = $this->userName;
        $attributes['displayName'] = $this->userName;
        $req = $req->withParsedBody($attributes);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 201);
        $attributes = json_decode($response->getBody(), true);
        $this->assertEquals(UserInterface::TYPE, $attributes['type']);
        $this->assertEquals($this->userName, $attributes['uniqueName']);
        $this->assertEquals($this->userName, $attributes['userName']);
        $this->assertNotEmpty($attributes['id']);
        $this->userId = $attributes['id'];
    }

    public function test111_GetUser() {
        $id = $this->getEntryId(UserInterface::TYPE);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Users/' . $id
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 201);
        $this->assertNotEmpty((string)$response->getBody());
    }

    public function test112_UpdateUser() {
        $id = $this->getEntryId(UserInterface::TYPE);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/Users/' . $id
        ]);
        $req = Request::createFromEnvironment($env);
        $attributes = array();
        $attributes['displayName'] = $this->userName . '_test';
        $req = $req->withParsedBody($attributes);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test113_GetUser() {
        $id = $this->getEntryId(UserInterface::TYPE);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Users/' . $id
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 201);
        $this->assertNotEmpty((string)$response->getBody());
        $attributes = json_decode($response->getBody(), true);
        $this->assertEquals($this->userName . '_test', $attributes['displayName']);
        $this->assertEquals($this->userName, $attributes['userName']);
        $this->assertEquals($this->userName, $attributes['uniqueName']);
    }

    public function test114_GetUsers() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Users'
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test115_DeleteUser() {
        $id = $this->getEntryId(UserInterface::TYPE);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI'    => '/v1/Users/' . $id
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 204);
    }

    public function test116_GetUserFail() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Users/rest_u_1'
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 404);
        $this->assertNotEmpty((string)$response->getBody());
    }

    //////////////////////////////////////////////////

    public function test220_CreateGroup() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/Groups',
        ]);
        $req = Request::createFromEnvironment($env);
        $attributes = array();
        $attributes['uniqueName'] = $this->groupName;
        $req = $req->withParsedBody($attributes);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 201);
    }

    public function test221_GetGroup() {
        $id = $this->getEntryId(GroupInterface::TYPE);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Groups/' . $id
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 201);
        $this->assertNotEmpty((string)$response->getBody());
    }

    public function test222_UpdateGroup() {
        $id = $this->getEntryId(GroupInterface::TYPE);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/Groups/' . $id
        ]);
        $req = Request::createFromEnvironment($env);
        $attributes = array();
        $attributes['displayName'] = $this->groupName . '_test';
        $req = $req->withParsedBody($attributes);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test223_GetGroup() {
        $id = $this->getEntryId(GroupInterface::TYPE);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Groups/' . $id
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 201);
        $this->assertNotEmpty((string)$response->getBody());
        $attributes = json_decode($response->getBody(), true);
        $this->assertEquals($this->groupName . '_test', $attributes['displayName']);
        $this->assertEquals($this->groupName, $attributes['uniqueName']);
    }

    public function test224_GetGroups() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Groups'
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test225_DeleteGroup() {
        $id = $this->getEntryId(GroupInterface::TYPE);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI'    => '/v1/Groups/' . $id
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 204);
    }

    public function test226_GetGroupFail() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Groups/rest_r_1'
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 404);
        $this->assertNotEmpty((string)$response->getBody());
    }
    
    //////////////////////////////////////////////////

    public function test330_CreateRole() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/Roles',
        ]);
        $req = Request::createFromEnvironment($env);
        $attributes = array();
        $attributes['uniqueName'] = $this->roleName;
        $req = $req->withParsedBody($attributes);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 201);
    }

    public function test331_GetRole() {
        $id = $this->getEntryId(RoleInterface::TYPE);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Roles/' . $id
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 201);
        $this->assertNotEmpty((string)$response->getBody());
    }

    public function test332_UpdateRole() {
        $id = $this->getEntryId(RoleInterface::TYPE);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/Roles/' . $id
        ]);
        $req = Request::createFromEnvironment($env);
        $attributes = array();
        $attributes['displayName'] = $this->roleName . '_test';
        $req = $req->withParsedBody($attributes);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test333_GetRole() {
        $id = $this->getEntryId(RoleInterface::TYPE);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Roles/' . $id
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 201);
        $this->assertNotEmpty((string)$response->getBody());
        $attributes = json_decode($response->getBody(), true);
        $this->assertEquals($this->roleName . '_test', $attributes['displayName']);
        $this->assertEquals($this->roleName, $attributes['uniqueName']);
    }

    public function test334_GetRoles() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Roles'
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test335_DeleteRole() {
        $id = $this->getEntryId(RoleInterface::TYPE);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI'    => '/v1/Roles/' . $id
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 204);
    }

    public function test336_GetRoleFail() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Roles/rest_r_1'
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 404);
        $this->assertNotEmpty((string)$response->getBody());
    }

    //////////////////////////////////////////////////

    private function getEntryId(string $type): string {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/' . $type . 's'
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        
        $entries = json_decode($response->getBody(), true);
        $entry = current($entries);
        return $entry['id'];
    }

}