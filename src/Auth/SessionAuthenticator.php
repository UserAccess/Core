<?php

namespace PragmaPHP\UserAccess\Auth;

use \PragmaPHP\UserAccess\UserAccess;
use \PragmaPHP\UserAccess\Entry\UserInterface;

class SessionAuthenticator {

    const SESSION_LOGIN_USERID = 'LOGIN_USERID';
    const SESSION_LOGIN_USERNAME = 'LOGIN_USERNAME';
    const SESSION_LOGIN_AUTHENTICATED = 'LOGIN_AUTHENTICATED';
    const HTTP_X_CSRF_TOKEN = 'HTTP_X_CSRF_TOKEN';

    private $userProvider;

    private static function setHeader(string $key, string $value) {
        header($key . ': ' . $value);
    }

    private static function echoJsonLogin(): array {
        if ($_SESSION[self::SESSION_LOGIN_AUTHENTICATED]) {
            return [
                self::SESSION_LOGIN_AUTHENTICATED => true, 
                self::SESSION_LOGIN_USERID => $_SESSION[self::SESSION_LOGIN_USERID],
                self::SESSION_LOGIN_USERNAME => $_SESSION[self::SESSION_LOGIN_USERNAME]
            ];
        } else {
            return [
                self::SESSION_LOGIN_AUTHENTICATED => false, 
                self::SESSION_LOGIN_USERID => '',
                self::SESSION_LOGIN_USERNAME => ''
            ];
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function startSession(): void {
        if (session_status() == PHP_SESSION_NONE) {
            $session_settings = [
                'httponly' => true,
                'samesite' => 'Strict'
            ];
            session_set_cookie_params($session_settings);
            session_start();
        }
        if (!array_key_exists(self::SESSION_LOGIN_AUTHENTICATED, $_SESSION)) {
            $_SESSION[self::SESSION_LOGIN_AUTHENTICATED] = false;
        }
        if (!array_key_exists(self::SESSION_LOGIN_USERID, $_SESSION)) {
            $_SESSION[self::SESSION_LOGIN_USERID] = '';
        }
        if (!array_key_exists(self::SESSION_LOGIN_USERNAME, $_SESSION)) {
            $_SESSION[self::SESSION_LOGIN_USERNAME] = '';
        }
        // if (!array_key_exists(SESSION_LOGIN_ATTEMPTS, $_SESSION)) {
        //     $_SESSION[SESSION_LOGIN_ATTEMPTS] = 0;
        // }
    }

    public static function login(UserProviderInterface $userProvider, string $userName, string $password): String {
        $userName = trim(strtolower($userName));
        $password = trim($password);
        if (empty($userName) || empty($password) || !$userProvider->isUniqueNameExisting($userName)) {
            // throw new \Exception(UserAccess::EXCEPTION_AUTHENTICATION_FAILED);
            return echoJsonLogin();
        }
        $users = $userProvider->findUsers('uniqueName', $uniqueName);
        if (empty($users) || \count($users) > 1) {
            // throw new \Exception(UserAccess::EXCEPTION_AUTHENTICATION_FAILED);
            return echoJsonLogin();
        } else {
            $user = current($users);
        }
        if (!$user->isActive() || $user->getLoginAttempts() > 10) {
            // throw new \Exception(UserAccess::EXCEPTION_AUTHENTICATION_FAILED);
            return echoJsonLogin();
        }
        if ($user->verifyPassword($secret)){
            $_SESSION[self::SESSION_LOGIN_USERID] = $user->getId();
            $_SESSION[self::SESSION_LOGIN_USERNAME] = $user->getUserName();
            $_SESSION[self::SESSION_LOGIN_AUTHENTICATED] = true;
            // $_SESSION[SESSION_LOGIN_ATTEMPTS] = 0;
            return echoJsonLogin();
        } else {
            // throw new \Exception(UserAccess::EXCEPTION_AUTHENTICATION_FAILED);
            return echoJsonLogin();
        }
    }

    public static function isLoggedIn(): bool {
        return (!empty($_SESSION) && array_key_exists(self::SESSION_LOGIN_AUTHENTICATED, $_SESSION) && $_SESSION[self::SESSION_LOGIN_AUTHENTICATED] === true);
    }

    public static function getLoginInfo(): array {
        return self::echoJsonLogin();
    }

    public static function enforceLoggedIn(): void {
        if (!self::isLoggedIn()) {
            http_response_code(401);
            setHeader('Content-Type', 'application/json');
            echo self::echoJsonLogin();
            exit();
        }
    }

    public static function setCsrfTokenHeader(): void {
        if (!array_key_exists(HTTP_X_CSRF_TOKEN, $_SESSION)) {
            $_SESSION[HTTP_X_CSRF_TOKEN] = bin2hex(random_bytes(32));;
        }
        if (array_key_exists(self::HTTP_X_CSRF_TOKEN, $_SERVER) && $_SERVER[self::HTTP_X_CSRF_TOKEN] === 'fetch') {
            setHeader(self::HTTP_X_CSRF_TOKEN, $_SESSION[self::HTTP_X_CSRF_TOKEN]);
        }
    }

    public static function logout(): array {
        $_SESSION[self::SESSION_LOGIN_USERID] = '';
        $_SESSION[self::SESSION_LOGIN_USERNAME] = '';
        $_SESSION[self::SESSION_LOGIN_AUTHENTICATED] = false;
        // $_SESSION[SESSION_LOGIN_ATTEMPTS] = 0;
        return self::echoJsonLogin();
    }

}