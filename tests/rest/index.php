<?php

session_start();

require '../../vendor/autoload.php';

use \UserAccess\UserAccess;
use \UserAccess\Entry\User;
use \UserAccess\Provider\FileUserProvider;
use \UserAccess\Provider\FileGroupProvider;
use \UserAccess\Provider\FileRoleProvider;
use \UserAccess\Rest\RestApp;

$userProvider = new FileUserProvider('../../data/users');
$groupProvider = new FileGroupProvider('../../data/groups');
$roleProvider = new FileRoleProvider('../../data/roles');
$userAccess = new UserAccess($userProvider, $groupProvider, $roleProvider);

if (!$userAccess->getUserProvider()->isUniqueNameExisting('Administrator')){
    $admin = new User('Administrator');
    $admin->setDisplayName('Administrator User');
    $admin->setEmail('administrator@useraccess.net');
    $admin->setPassword('abcd1234');
    $userAccess->getUserProvider()->createUser($admin);
}

$app = new RestApp($userAccess, '/tests/rest');
$app->run();