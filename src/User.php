<?php namespace UserAccess\Core;

class User {

    private $userId = '';
    private $passwordHash = '';

    public function __construct($userId) {
        $this->userId = trim($userId);
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setPassword($password) {
        $this->passwordHash = \password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword($password) {
        return \password_verify($password, $this->passwordHash);
    }

    public function getAttributes() {
        return $array = [
            'userId' => $this->userId
        ];
    }

}