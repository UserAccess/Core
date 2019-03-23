<?php

namespace UserAccess\Provider;

use \UserAccess\UserAccess;
use \UserAccess\Entry\EntryInterface;
use \UserAccess\Entry\Role;
use \UserAccess\Entry\User;
use \UserAccess\Provider\EntryProviderInterface;

use \Filebase\Database;
use \Filebase\Format\Yaml;
use \Filebase\Format\Json;

abstract class AbstractFilebaseEntryProvider implements EntryProviderInterface {

    protected $db;
    protected $type;

    public function __construct(string $type, string $directory = 'data', string $format = 'YAML') {
        $this->type = $type;
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
            ]
        ]);
    }

    public function isEntryExisting(string $id): bool {
        $id = \strtoupper($id);
        return $this->db->has($id);
    }

    public function isProviderReadOnly(): bool {
        return false;
    }

    public function createEntry(EntryInterface $entry) {
        $id = $entry->getId();
        if ($this->isEntryExisting($id)) {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_ALREADY_EXIST);
        } else {
            $entry->setReadOnly($this->isProviderReadOnly());
            $item = $this->db->get($id);
            $item->set($entry->getAttributes())->save();
        }
    }

    public function getEntry(string $id): EntryInterface {
        $id = \strtoupper($id);
        if ($this->isEntryExisting($id)) {
            $attributes = $this->db->get($id)->toArray();
            $entry;
            switch ($this->type) {
                case User::TYPE:
                    $entry = new User($id);
                    break;
                case Role::TYPE:
                    $entry = new Role($id);
                    break;
                default:
                    throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
            }
            $entry->setAttributes($attributes);
            return $entry;
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function getEntries(): array {
        $items = $this->db->findAll();
        return $this->itemsToImpl($items);
    }

    public function findEntries(string $attributeName, string $attributeValue, string $comparisonOperator): array {
        $attributeValue = \trim($attributeValue);
        $items = [];
        switch ($comparisonOperator) {
            case UserAccess::COMPARISON_EQUAL:
                //$items = $this->db->where($attributeName, '===', $attributeValue)->resultDocuments();
                $items = $this->db->where($attributeName,'REGEX','/' . $attributeValue . '/i')->resultDocuments();
                break;
            case UserAccess::COMPARISON_LIKE:
                $items = $this->db->where($attributeName, 'LIKE', $attributeValue)->resultDocuments();
                break;
            default:
                $items = $this->db->where($attributeName, '=', $attributeValue)->resultDocuments();
                break;
        }
        return $this->itemsToImpl($items);
    }
    
    public function updateEntry(EntryInterface $entry) {
        $id = $entry->getId();
        if ($this->isEntryExisting($id)) {
            $item = $this->db->get($id);
            $item->set($entry->getAttributes())->save();
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function deleteEntry(string $id) {
        $id = \strtoupper($id);
        if ($this->isEntryExisting($id)) {
            $this->db->delete($this->db->get($id));
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    private function itemsToImpl(array $items): array {
        $result = [];
        foreach($items as $item){
            $id = $item->id;
            $attributes = $item->toArray();
            $entry;
            switch ($this->type) {
                case User::TYPE:
                    $entry = new User($id);
                    break;
                case Role::TYPE:
                    $entry = new Role($id);
                    break;
                default:
                    throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
            }
            $entry->setAttributes($attributes);
            $result[$id] = $entry;
        }
        return $result;
    }

}