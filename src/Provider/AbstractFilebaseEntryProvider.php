<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\Entry\EntryInterface;
use \UserAccess\Core\Provider\EntryProviderInterface;

use \Filebase\Database;
use \Filebase\Format\Yaml;
use \Filebase\Format\Json;

abstract class AbstractFilebaseEntryProvider implements EntryProviderInterface {

    protected $db;

    public function __construct(string $directory = 'data', string $format = 'YAML') {
        $this->db = new Database([
            'dir' => $directory,
            'format' => $format == 'YAML' ? Yaml::class : Json::class,
            'validate' => [
                'id' => [
                    'valid.type' => 'string',
                    'valid.required' => true
                ],
                'type' => [
                    'valid.type' => 'string',
                    'valid.required' => true
                ],
                'displayName' => [
                    'valid.type' => 'string',
                    'valid.required' => false
                ]
                // 'passwordHash' => [
                //     'valid.type'     => 'string',
                //     'valid.required' => false
                // ],
                // 'email' => [
                //     'valid.type' => 'string',
                //     'valid.required' => false
                // ],
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

    public function isExisting(string $id): bool {
        return $this->db->has($id);
    }

}