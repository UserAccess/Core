<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\Provider\ProviderInterface;
use \UserAccess\Core\Entry\UserInterface;
use \UserAccess\Core\Entry\User;

use \Filebase\Database;
use \Filebase\Format\Yaml;
use \Filebase\Format\Json;

class FilebaseProvider implements ProviderInterface {

    private $database;

    public function __construct(string $directory = 'data', string $format = 'YAML') {
        $this->db = new Database([
            'dir' => $directory,
            'format' => $format == 'YAML' ? Yaml::class : Json::class,
            'validate' => [
                'id' => [
                    'valid.type' => 'string',
                    'valid.required' => true
                ],
                'displayName' => [
                    'valid.type' => 'string',
                    'valid.required' => false
                ],
                'passwordHash' => [
                    'valid.type'     => 'string',
                    'valid.required' => false
                ],
                'email' => [
                    'valid.type' => 'string',
                    'valid.required' => false
                ],
                // 'locked' => [
                //     'valid.type' => 'integer',
                //     'valid.required' => false
                // ],
                // 'failedLoginAttempts' => [
                //     'valid.type'     => 'integer',
                //     'valid.required' => false
                // ],
                // 'roles' => [
                //     'valid.type'     => 'array',
                //     'valid.required' => false
                // ],
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

    public function getUser(string $id): UserInterface {
        if ($this->isUserExisting($id)) {
            $user = new User($id);
            $user->setAttributes($this->db->get($id)->toArray());
            return $user;
        } else {
            throw new \Exception('User with ' . $id . ' not available');
        }
    }

    public function getAllUsers(): array {
        $result = [];
        $items = $this->db->findAll();
        foreach($items as $item){
            $user = new User($item->id);
            $user->setAttributes($item->toArray());
            $result[] = $user;
        }
        return $result;
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