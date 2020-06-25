<?php

namespace PragmaPHP\UserAccess\Util;

class Password {

    public static function hash(string $password) {
        return \password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verify(string $password, string $passwordHash) {
        return \password_verify($password, $passwordHash);
    }

}