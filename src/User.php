<?php

namespace UserAccess\Core;

use UserAccess\Core\Password;

class User {

    private $userId = '';
    private $passwordHash = '';

    public function __construct($userId) {
        $this->userId = trim($userId);
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setPassword(string $password) {
        $this->passwordHash = Password::hash($password);
    }

    public function verifyPassword($password) {
        return Password::verify($password, $this->passwordHash);
    }

    public function getAttributes() {
        return $array = [
            'userId' => $this->userId
        ];
    }

}