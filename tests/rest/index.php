<?php

session_start();

require '../../vendor/autoload.php';

use \UserAccess\UserAccess;
use \UserAccess\Auth\SessionAuthenticator;
use \UserAccess\Entry\User;
use \UserAccess\Provider\FilebaseUserProvider;
use \UserAccess\Provider\FilebaseRoleProvider;
use \UserAccess\Rest\RestApp;

$userProvider = new FilebaseUserProvider('../../data/users');
$roleProvider = new FilebaseRoleProvider('../../data/roles');
$userAccess = new UserAccess($userProvider, $roleProvider, new SessionAuthenticator());

$admin = new User('Administrator');
$admin->setDisplayName('Administrator User');
$admin->setEmail('administrator@useraccess.net');
$admin->setPassword('abcd1234');
$userAccess->getInbuiltUserProvider()->createUser($admin);

$app = new RestApp($userAccess);
$app->run();