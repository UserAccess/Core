<?php

namespace UserAccess\Rest;

use UserAccess\UserAccess;
use UserAccess\Entry\User;
use UserAccess\Entry\Group;
use UserAccess\Entry\Role;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class RestApp {

    private $app;
    private $container;

    public function __construct(UserAccess $userAccess) {

        $this->container = new \Slim\Container;

        $this->container
            ->get('settings')
            ->replace([
                'displayErrorDetails' => true,
                //'determineRouteBeforeAppMiddleware' => true,
                'addContentLengthHeader' => true,
                'debug' => false
            ]);

        $this->container['errorHandler'] = function ($container) {
            return function ($request, $response, $exception) use ($container) {
                return $response->withStatus(404)
                    ->withHeader('Content-Type', 'text/html')
                    ->write($exception->getMessage());
            };
        };

        $this->container['userAccess'] = $userAccess;

        $this->app = new \Slim\App($this->container);

        //////////////////////////////////////////////////

        $this->app->post('/v1/Me/login', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $attributes = filter_var_array($request->getParsedBody(), FILTER_SANITIZE_STRING);
            if (!array_key_exists('id', $attributes) || !array_key_exists('password', $attributes)) {
                throw new \Exception(UserAccess::EXCEPTION_AUTHENTICATION_FAILED);
            }
            $userAccess->selfserviceLogin($attributes['id'], $attributes['password']);
        });

        $this->app->post('/v1/Me/logout', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $userAccess->selfserviceLogout();
        });

        //////////////////////////////////////////////////

        $this->app->get('/v1/Users', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $entries = $userAccess->getUserProvider()->getUsers();
            $result = [];
            foreach($entries as $entry){
                $result[] = self::filterPassword($entry->getAttributes());
            }
            return $response->withStatus(200)->withHeader('Content-Type', 'application/scim+json')->withJson($result);
        });

        $this->app->get('/v1/Users/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $entry = $userAccess->getUserProvider()->getUser($args['id']);
            return $response->withStatus(201)->withHeader('Content-Type', 'application/scim+json')->withJson(self::filterPassword($entry->getAttributes()));
        });

        $this->app->post('/v1/Users', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $attributes = filter_var_array($request->getParsedBody(), FILTER_SANITIZE_STRING);
            if (!array_key_exists('userName', $attributes)) {
                throw new \Exception(UserAccess::EXCEPTION_INVALID_UNIQUE_NAME);
            }
            if ($userAccess->getUserProvider()->isUniqueNameExisting($attributes['userName'])) {
                throw new \Exception(UserAccess::EXCEPTION_ENTRY_ALREADY_EXIST);
            }
            if (!empty($attributes['email'])) {
                $find = $userAccess->getUserProvider()->findUsers('email', $attributes['email'], UserAccess::COMPARISON_EQUAL_IGNORE_CASE);
                if (!empty($find)) {
                    throw new \Exception(UserAccess::EXCEPTION_DUPLICATE_EMAIL);
                }
            }
            $entry = new User($attributes['userName']);
            $entry->setAttributes($attributes);
            $entry = $userAccess->getUserProvider()->createUser($entry);
            return $response->withStatus(201)->withHeader('Content-Type', 'application/scim+json')->withJson(self::filterPassword($entry->getAttributes()));
        });

        $this->app->post('/v1/Users/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $attributes = filter_var_array($request->getParsedBody(), FILTER_SANITIZE_STRING);
            $entry = $userAccess->getUserProvider()->getUser($args['id']);
            if (!empty($attributes['email'])) {
                $email = \trim(\strtolower($attributes['email']));
                if (strcasecmp($email, $entry->getEmail()) != 0) {
                    $find = $userAccess->getUserProvider()->findUsers('email', $email, UserAccess::COMPARISON_EQUAL_IGNORE_CASE);
                    if (!empty($find)) {
                        throw new \Exception(UserAccess::EXCEPTION_DUPLICATE_EMAIL);
                    }
                }
            }
            $entry->setAttributes($attributes);
            $userAccess->getUserProvider()->updateUser($entry);
            return $response->withStatus(200)->withHeader('Content-Type', 'application/scim+json')->withJson(self::filterPassword($entry->getAttributes()));
        });

        $this->app->delete('/v1/Users/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $userAccess->getUserProvider()->deleteUser($args['id']);
            return $response->withStatus(204);
        });

        //////////////////////////////////////////////////

        $this->app->get('/v1/Groups', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $entries = $userAccess->getGroupProvider()->getGroups();
            $result = [];
            foreach($entries as $entry){
                $result[] = $entry->getAttributes();
            }
            return $response->withStatus(200)->withHeader('Content-Type', 'application/scim+json')->withJson($result);
        });

        $this->app->get('/v1/Groups/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $entry = $userAccess->getGroupProvider()->getGroup($args['id']);
            return $response->withStatus(201)->withHeader('Content-Type', 'application/scim+json')->withJson($entry->getAttributes());
        });

        $this->app->post('/v1/Groups', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $attributes = filter_var_array($request->getParsedBody(), FILTER_SANITIZE_STRING);
            $entry = new Group($attributes['uniqueName']);
            $entry->setAttributes($attributes);
            $entry = $userAccess->getGroupProvider()->createGroup($entry);
            return $response->withStatus(201)->withHeader('Content-Type', 'application/scim+json')->withJson($entry->getAttributes());
        });

        $this->app->post('/v1/Groups/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $attributes = filter_var_array($request->getParsedBody(), FILTER_SANITIZE_STRING);
            $entry = $userAccess->getGroupProvider()->getGroup($args['id']);
            $entry->setAttributes($attributes);
            $entry = $userAccess->getGroupProvider()->updateGroup($entry);
            return $response->withStatus(200)->withHeader('Content-Type', 'application/scim+json')->withJson($entry->getAttributes());
        });

        $this->app->delete('/v1/Groups/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $userAccess->getGroupProvider()->deleteGroup($args['id']);
            return $response->withStatus(204);
        });
        
        //////////////////////////////////////////////////

        $this->app->get('/v1/Roles', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $entries = $userAccess->getRoleProvider()->getRoles();
            $result = [];
            foreach($entries as $entry){
                $result[] = $entry->getAttributes();
            }
            return $response->withStatus(200)->withHeader('Content-Type', 'application/scim+json')->withJson($result);
        });

        $this->app->get('/v1/Roles/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $entry = $userAccess->getRoleProvider()->getRole($args['id']);
            return $response->withStatus(201)->withHeader('Content-Type', 'application/scim+json')->withJson($entry->getAttributes());
        });

        $this->app->post('/v1/Roles', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $attributes = filter_var_array($request->getParsedBody(), FILTER_SANITIZE_STRING);
            $entry = new Role($attributes['uniqueName']);
            $entry->setAttributes($attributes);
            $entry = $userAccess->getRoleProvider()->createRole($entry);
            return $response->withStatus(201)->withHeader('Content-Type', 'application/scim+json')->withJson($entry->getAttributes());
        });

        $this->app->post('/v1/Roles/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $attributes = filter_var_array($request->getParsedBody(), FILTER_SANITIZE_STRING);
            $entry = $userAccess->getRoleProvider()->getRole($args['id']);
            $entry->setAttributes($attributes);
            $entry = $userAccess->getRoleProvider()->updateRole($entry);
            return $response->withStatus(200)->withHeader('Content-Type', 'application/scim+json')->withJson($entry->getAttributes());
        });

        $this->app->delete('/v1/Roles/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $userAccess->getRoleProvider()->deleteRole($args['id']);
            return $response->withStatus(204);
        });

    }

    public function run($silent = false) {
        return $this->app->run($silent);
    }

    public function getApp() {
        return $this->app;
    }

    //////////////////////////////////////////////////

    private static function filterPassword(array $attributes): array {
        if (array_key_exists('passwordHash', $attributes)) {
            unset($attributes['passwordHash']);
        }
        return $attributes;
    }


}