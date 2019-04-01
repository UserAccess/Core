<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\UserAccess;
use \UserAccess\Entry\UserInterface;
use \UserAccess\Entry\RoleInterface;
use \UserAccess\Provider\FilebaseUserProvider;
use \UserAccess\Provider\FilebaseRoleProvider;
use \UserAccess\Rest\RestApp;
use \Slim\Http\Environment;
use \Slim\Http\Request;

class RestAppTest extends TestCase {

    private $app;

    private $userName = 'restu1';
    private $userId = '';
    private $roleName = 'restr1';
    private $roleId = '';

    public function setUp() {
        $userProvider = new FilebaseUserProvider('testdata/users');
        $roleProvider = new FilebaseRoleProvider('testdata/roles');
        $userAccess = new UserAccess($userProvider, $roleProvider);
        $this->app = new RestApp($userAccess);
    }

    public function test10_CreateUser() {
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

    public function test11_GetUser() {
        $id = $this->getEntryId(UserInterface::TYPE);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Users/' . $id
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame($response->getStatusCode(), 201);
        $this->assertNotEmpty((string)$response->getBody());
    }

    public function test12_UpdateUser() {
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
        $response = $this->app->run(false);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test13_GetUser() {
        $id = $this->getEntryId(UserInterface::TYPE);
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Users/' . $id
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(false);
        $this->assertSame($response->getStatusCode(), 201);
        $this->assertNotEmpty((string)$response->getBody());
        $attributes = json_decode($response->getBody(), true);
        $this->assertEquals($this->userName . '_test', $attributes['displayName']);
        $this->assertEquals($this->userName, $attributes['userName']);
        $this->assertEquals($this->userName, $attributes['uniqueName']);
    }

    public function test14_GetUsers() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Users'
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test15_DeleteUser() {
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

    public function test16_GetUserFail() {
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

    public function test20_CreateRole() {
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

    public function test21_GetRole() {
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

    public function test22_UpdateRole() {
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

    public function test23_GetRole() {
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

    public function test24_GetRoles() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Roles'
        ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test25_DeleteRole() {
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

    public function test26_GetRoleFail() {
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