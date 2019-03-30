<?php

use \PHPUnit\Framework\TestCase;

use \UserAccess\UserAccess;
use \UserAccess\Provider\FilebaseUserProvider;
use \UserAccess\Provider\FilebaseRoleProvider;
use \UserAccess\Rest\RestApp;
use \Slim\Http\Environment;
use \Slim\Http\Request;

class RestAppTest extends TestCase {

    private $app;

    public function setUp() {
        $userProvider = new FilebaseUserProvider('testdata/users');
        $roleProvider = new FilebaseRoleProvider('testdata/roles');
        $userAccess = new UserAccess($userProvider, $roleProvider);
        $this->app = new RestApp($userAccess);
    }

    public function test11_CreateUser() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/Users'
        ]);
        $req = Request::createFromEnvironment($env);
        $attributes = array();
        $attributes['id'] = 'rest_u_1';
        $req = $req->withParsedBody($attributes);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test12_UpdateUser() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/Users/rest_u_1',
        ]);
        $req = Request::createFromEnvironment($env);
        $attributes = array();
        $attributes['displayName'] = 'rest_u_1 Test';
        $req = $req->withParsedBody($attributes);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test13_GetUser() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Users/rest_u_1'
            ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $this->assertNotEmpty((string)$response->getBody());
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
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI'    => '/v1/Users/rest_u_1'
            ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
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

    public function test21_CreateRole() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/Roles',
        ]);
        $req = Request::createFromEnvironment($env);
        $attributes = array();
        $attributes['id'] = 'rest_r_1';
        $req = $req->withParsedBody($attributes);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test22_UpdateRole() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI'    => '/v1/Roles/rest_r_1',
        ]);
        $req = Request::createFromEnvironment($env);
        $attributes = array();
        $attributes['displayName'] = 'roleidrest1 Test';
        $req = $req->withParsedBody($attributes);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test23_GetRole() {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/v1/Roles/rest_r_1'
            ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
        $this->assertNotEmpty((string)$response->getBody());
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
        $env = Environment::mock([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI'    => '/v1/Roles/rest_r_1'
            ]);
        $req = Request::createFromEnvironment($env);
        $this->app->getApp()->getContainer()['request'] = $req;
        $response = $this->app->run(true);
        $this->assertSame($response->getStatusCode(), 200);
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

}