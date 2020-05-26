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

use \Slim\Psr7\Headers;
use \Slim\Psr7\Request;
use \Slim\Psr7\Uri;
use \Slim\Psr7\Factory\StreamFactory;


class RestAppTest extends TestCase {

    private $app;
    private $userName = 'restu1';
    private $userId = '';
    private $groupName = 'restg1';
    private $groupId = '';
    private $roleName = 'restg1';
    private $roleId = '';

    public function setUp(): void {
        $userProvider = new FilebaseUserProvider('testdata/users');
        $groupProvider = new FilebaseGroupProvider('testdata/groups');
        $roleProvider = new FilebaseRoleProvider('testdata/roles');
        $userAccess = new UserAccess($userProvider, $groupProvider, $roleProvider);
        $this->app = new RestApp($userAccess);
    }

    private function createRequest(string $method, string $path, array $headers = ['HTTP_ACCEPT' => 'application/json'], array $cookies = [], array $serverParams = []): Request {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);
        $header = new Headers();
        foreach ($headers as $name => $value) {
            $header->addHeader($name, $value);
        }
        return new Request($method, $uri, $header, $cookies, $serverParams, $stream);
    }

    public function test110_CreateUser() {
        $req = $this->createRequest('POST', '/v1/Users');
        $attributes = array();
        $attributes['userName'] = $this->userName;
        $attributes['displayName'] = $this->userName;
        $req = $req->withParsedBody($attributes);
        $response = $this->app->getApp()->handle($req);
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
        $req = $this->createRequest('GET', '/v1/Users/' . $id);
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 201);
        $this->assertNotEmpty((string)$response->getBody());
    }

    public function test112_UpdateUser() {
        $id = $this->getEntryId(UserInterface::TYPE);
        $req = $this->createRequest('POST', '/v1/Users/' . $id);
        $attributes = array();
        $attributes['displayName'] = $this->userName . '_test';
        $req = $req->withParsedBody($attributes);
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test113_GetUser() {
        $id = $this->getEntryId(UserInterface::TYPE);
        $req = $this->createRequest('GET', '/v1/Users/' . $id);
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 201);
        $this->assertNotEmpty((string)$response->getBody());
        $attributes = json_decode($response->getBody(), true);
        $this->assertEquals($this->userName . '_test', $attributes['displayName']);
        $this->assertEquals($this->userName, $attributes['userName']);
        $this->assertEquals($this->userName, $attributes['uniqueName']);
    }

    public function test114_GetUsers() {
        $req = $this->createRequest('GET', '/v1/Users');
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test115_DeleteUser() {
        $id = $this->getEntryId(UserInterface::TYPE);
        $req = $this->createRequest('DELETE', '/v1/Users/' . $id);
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 204);
    }

    public function test116_GetUserFail() {
        $req = $this->createRequest('GET', '/v1/Users/rest_u_1');
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 404);
        $this->assertNotEmpty((string)$response->getBody());
    }

    //////////////////////////////////////////////////

    public function test220_CreateGroup() {
        $req = $this->createRequest('POST', '/v1/Groups');
        $attributes = array();
        $attributes['uniqueName'] = $this->groupName;
        $req = $req->withParsedBody($attributes);
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 201);
    }

    public function test221_GetGroup() {
        $id = $this->getEntryId(GroupInterface::TYPE);
        $req = $this->createRequest('GET', '/v1/Groups/' . $id);
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 201);
        $this->assertNotEmpty((string)$response->getBody());
    }

    public function test222_UpdateGroup() {
        $id = $this->getEntryId(GroupInterface::TYPE);
        $req = $this->createRequest('POST', '/v1/Groups/' . $id);
        $attributes = array();
        $attributes['displayName'] = $this->groupName . '_test';
        $req = $req->withParsedBody($attributes);
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test223_GetGroup() {
        $id = $this->getEntryId(GroupInterface::TYPE);
        $req = $this->createRequest('GET', '/v1/Groups/' . $id);
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 201);
        $this->assertNotEmpty((string)$response->getBody());
        $attributes = json_decode($response->getBody(), true);
        $this->assertEquals($this->groupName . '_test', $attributes['displayName']);
        $this->assertEquals($this->groupName, $attributes['uniqueName']);
    }

    public function test224_GetGroups() {
        $req = $this->createRequest('GET', '/v1/Groups');
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test225_DeleteGroup() {
        $id = $this->getEntryId(GroupInterface::TYPE);
        $req = $this->createRequest('DELETE', '/v1/Groups/' . $id);
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 204);
    }

    public function test226_GetGroupFail() {
        $req = $this->createRequest('GET', '/v1/Groups/rest_r_1');
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 404);
        $this->assertNotEmpty((string)$response->getBody());
    }
    
    //////////////////////////////////////////////////

    public function test330_CreateRole() {
        $req = $this->createRequest('POST', '/v1/Roles');
        $attributes = array();
        $attributes['uniqueName'] = $this->roleName;
        $req = $req->withParsedBody($attributes);
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 201);
    }

    public function test331_GetRole() {
        $id = $this->getEntryId(RoleInterface::TYPE);
        $req = $this->createRequest('GET', '/v1/Roles/' . $id);
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 201);
        $this->assertNotEmpty((string)$response->getBody());
    }

    public function test332_UpdateRole() {
        $id = $this->getEntryId(RoleInterface::TYPE);
        $req = $this->createRequest('POST', '/v1/Roles/' . $id);
        $attributes = array();
        $attributes['displayName'] = $this->roleName . '_test';
        $req = $req->withParsedBody($attributes);
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test333_GetRole() {
        $id = $this->getEntryId(RoleInterface::TYPE);
        $req = $this->createRequest('GET', '/v1/Roles/' . $id);
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 201);
        $this->assertNotEmpty((string)$response->getBody());
        $attributes = json_decode($response->getBody(), true);
        $this->assertEquals($this->roleName . '_test', $attributes['displayName']);
        $this->assertEquals($this->roleName, $attributes['uniqueName']);
    }

    public function test334_GetRoles() {
        $req = $this->createRequest('GET', '/v1/Roles');
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function test335_DeleteRole() {
        $id = $this->getEntryId(RoleInterface::TYPE);
        $req = $this->createRequest('DELETE', '/v1/Roles/' . $id);
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 204);
    }

    public function test336_GetRoleFail() {
        $req = $this->createRequest('GET', '/v1/Roles/rest_r_1');
        $response = $this->app->getApp()->handle($req);
        $this->assertSame($response->getStatusCode(), 404);
        $this->assertNotEmpty((string)$response->getBody());
    }

    //////////////////////////////////////////////////

    private function getEntryId(string $type): string {
        $req = $this->createRequest('GET', '/v1/' . $type . 's');
        $response = $this->app->getApp()->handle($req);
        $entries = json_decode($response->getBody(), true);
        $entry = current($entries);
        return $entry['id'];
    }

}