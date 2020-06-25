<?php

session_start();

require '../../vendor/autoload.php';

use \PragmaPHP\UserAccess\UserAccess;
use \PragmaPHP\UserAccess\Entry\User;
use \PragmaPHP\UserAccess\Provider\FileUserProvider;
use \PragmaPHP\UserAccess\Provider\FileGroupProvider;
use \PragmaPHP\UserAccess\Provider\FileRoleProvider;
use \PragmaPHP\UserAccess\Rest\RestApp;

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