<?php

namespace UserAccess\Rest;

use UserAccess\UserAccess;
use UserAccess\Entry\User;
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

        $this->app->post('/selfservice/login', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $attributes = filter_var_array($request->getParsedBody(), FILTER_SANITIZE_STRING);
            if (!array_key_exists('id', $attributes) || !array_key_exists('password', $attributes)) {
                throw new \Exception(UserAccess::EXCEPTION_AUTHENTICATION_FAILED);
            }
            $userAccess->selfserviceLogin($attributes['id'], $attributes['password']);
        });

        $this->app->post('/selfservice/logout', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $userAccess->selfserviceLogout();
        });

        //////////////////////////////////////////////////

        $this->app->get('/users', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $entries = $userAccess->getUsers();
            $result = [];
            foreach($entries as $entry){
                $result[] = self::filterPassword($entry->getAttributes());
            }
            return $response->withJson($result);
        });

        $this->app->get('/users/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $entry = $userAccess->getUser($args['id']);
            return $response->withJson(self::filterPassword($entry->getAttributes()));
        });

        $this->app->post('/users', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $attributes = filter_var_array($request->getParsedBody(), FILTER_SANITIZE_STRING);
            if (!array_key_exists('id', $attributes)) {
                throw new \Exception(UserAccess::EXCEPTION_INVALID_ID);
            }
            if ($userAccess->isUserExisting($attributes['id'])) {
                throw new \Exception(UserAccess::EXCEPTION_ENTRY_ALREADY_EXIST);
            }
            if (!empty($attributes['email'])) {
                $find = $userAccess->findUsers('email', $attributes['email'], UserAccess::COMPARISON_EQUAL);
                if (!empty($find)) {
                    throw new \Exception(UserAccess::EXCEPTION_DUPLICATE_EMAIL);
                }
            }
            $entry = new User($attributes['id']);
            $entry->setAttributes($attributes);
            $userAccess->getUserProvider()->createUser($entry);
        });

        $this->app->post('/users/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $attributes = filter_var_array($request->getParsedBody(), FILTER_SANITIZE_STRING);
            $entry = $userAccess->getUser($args['id']);
            if (!empty($attributes['email'])) {
                $email = \trim(\strtolower($attributes['email']));
                if (strcasecmp($email, $entry->getEmail()) != 0) {
                    $find = $userAccess->findUsers('email', $email, UserAccess::COMPARISON_EQUAL);
                    if (!empty($find)) {
                        throw new \Exception(UserAccess::EXCEPTION_DUPLICATE_EMAIL);
                    }
                }
            }
            $entry->setAttributes($attributes);
            $userAccess->getUserProvider()->updateUser($entry);
        });

        $this->app->delete('/users/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $userAccess->getUserProvider()->deleteUser($args['id']);
        });

        //////////////////////////////////////////////////

        $this->app->get('/roles', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $entries = $userAccess->getRoles();
            $result = [];
            foreach($entries as $entry){
                $result[] = $entry->getAttributes();
            }
            return $response->withJson($result);
        });

        $this->app->get('/roles/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $entry = $userAccess->getRole($args['id']);
            return $response->withJson($entry->getAttributes());
        });

        $this->app->post('/roles', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $attributes = filter_var_array($request->getParsedBody(), FILTER_SANITIZE_STRING);
            $entry = new Role($attributes['id']);
            $entry->setAttributes($attributes);
            $userAccess->getRoleProvider()->createRole($entry);
        });

        $this->app->post('/roles/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $attributes = filter_var_array($request->getParsedBody(), FILTER_SANITIZE_STRING);
            $entry = $userAccess->getRole($args['id']);
            $entry->setAttributes($attributes);
            $userAccess->getRoleProvider()->updateRole($entry);
        });

        $this->app->delete('/roles/{id}', function (Request $request, Response $response, array $args) {
            $userAccess = $this->userAccess;
            $userAccess->getRoleProvider()->deleteRole($args['id']);
        });

    }

    public function run() {
        return $this->app->run();
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