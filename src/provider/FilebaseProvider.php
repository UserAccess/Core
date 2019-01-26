<?php

namespace UserAccess\Core\Provider;

use UserAccess\Core\Provider\ProviderInterface;
use UserAccess\Core\Entry\UserInterface;
use UserAccess\Core\Entry\User;

use Filebase\Database;
use Filebase\Format\Yaml;
use Filebase\Format\Json;

class FilebaseProvider implements ProviderInterface {

    private $database;

    public function __construct(string $directory = 'data', string $format = 'YAML') {
        $this->db = new Database([
            'dir' => $directory,
            'format' => $format == 'YAML' ? Yaml::class : Json::class,
            'validate' => [
                'email' => [
                    'valid.type' => 'string',
                    'valid.required' => true
                ],
                'firstname' => [
                    'valid.type' => 'string',
                    'valid.required' => false
                ],
                'lastname' => [
                    'valid.type'     => 'string',
                    'valid.required' => false
                ],
                'passwordHash' => [
                    'valid.type'     => 'string',
                    'valid.required' => false
                ]
            ]
        ]);
    }

    public function isUserExisting(string $id): bool {
        return $this->db->has($id);
    }

    public function createUser(UserInterface $user) {
        $id = $user->getId();
        if ($this->isUserExisting($id)) {
            throw new \Exception('User with ' . $id . ' already available');
        } else {
            $item = $this->db->get($id);
            $item->set($user->getAttributes())->save();
        }
    }

    public function readUser(string $id): UserInterface {
        if ($this->isUserExisting($id)) {
            $user = new User($id);
            $user->setAttributes($this->db->get($id)->toArray());
            return $user;
        } else {
            throw new \Exception('User with ' . $id . ' not available');
        }
    }

    public function updateUser(UserInterface $user) {
        $id = $user->getId();
        if ($this->isUserExisting($id)) {
            $item = $this->db->get($id);
            $item->set($user->getAttributes())->save();
        } else {
            throw new \Exception('User with ' . $id . ' not available');
        }
    }

    public function deleteUser(string $id) {
        if ($this->isUserExisting($id)) {
            $this->db->delete($this->db->get($id));
        } else {
            throw new \Exception('User with ' . $id . ' not available');
        }
    }

}