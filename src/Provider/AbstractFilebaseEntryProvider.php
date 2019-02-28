<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\UserAccess;
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

    public function isEntryExisting(string $id): bool {
        return $this->db->has($id);
    }

    public function isProviderReadOnly(): bool {
        return false;
    }

    public function createEntry(EntryInterface $entry) {
        $id = $entry->getId();
        $type = $entry->getType();
        if ($this->isEntryExisting($id)) {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_ALREADY_EXIST);
        } else {
            $entry->setReadOnly($this->isProviderReadOnly());
            $item = $this->db->get($id);
            $item->set($entry->getAttributes())->save();
        }
    }

    public function updateEntry(EntryInterface $entry) {
        $id = $entry->getId();
        $type = $entry->getType();
        if ($this->isEntryExisting($id)) {
            $item = $this->db->get($id);
            $item->set($entry->getAttributes())->save();
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function deleteEntry(string $id) {
        if ($this->isEntryExisting($id)) {
            $this->db->delete($this->db->get($id));
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

}