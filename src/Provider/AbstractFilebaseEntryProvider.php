<?php

namespace UserAccess\Provider;

use \UserAccess\UserAccess;
use \UserAccess\Entry\EntryInterface;
use \UserAccess\Entry\Role;
use \UserAccess\Entry\RoleInterface;
use \UserAccess\Entry\User;
use \UserAccess\Entry\UserInterface;
use \UserAccess\Provider\EntryProviderInterface;

use \Filebase\Database;
use \Filebase\Document;
use \Filebase\Format\Yaml;
use \Filebase\Format\Json;

use \Ramsey\Uuid\Uuid;

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
                'uniqueName' => [
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

    public function isIdExisting(string $id): bool {
        $id = \trim($id);
        return $this->db->has($id);
    }

    public function isUniqueNameExisting(string $uniqueName): bool {
        $uniqueName = \trim(\strtolower($uniqueName));
        $entries = $this->findEntries('uniqueName', $uniqueName, UserAccess::COMPARISON_EQUAL);
        if (empty($entries)) {
            return false;
        } else {
            if (count($entries) === 1) {
                return true;
            } else {
                throw new \Exception(UserAccess::EXCEPTION_INVALID_VALUE);
            }
        }
    }

    public function isReadOnly(): bool {
        return false;
    }

    public function createEntry(EntryInterface $entry): EntryInterface {
        $uniqueName = $entry->getUniqueName();
        if ($this->isUniqueNameExisting($uniqueName)) {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_ALREADY_EXIST);
        } else {
            // $entry->setReadOnly($this->isReadOnly());
            $id = Uuid::uuid4()->toString();
            $entry->setId($id);
            $item = $this->db->get($id);
            $item->set($entry->getAttributes())->save();
            return $entry;
        }
    }

    public function getEntry(string $id): EntryInterface {
        $id = \trim($id);
        if ($this->isIdExisting($id)) {
            $attributes = $this->db->get($id)->toArray();
            $uniqueName = $attributes['uniqueName'];
            $entry;
            switch ($this->type) {
                case UserInterface::TYPE:
                    $entry = new User($uniqueName);
                    break;
                case RoleInterface::TYPE:
                    $entry = new Role($uniqueName);
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
    
    public function updateEntry(EntryInterface $entry): EntryInterface {
        $id = $entry->getId();
        if ($this->isIdExisting($id)) {
            $item = $this->db->get($id);
            $item->set($entry->getAttributes())->save();
            return $entry;
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function deleteEntry(string $id) {
        $id = \trim(\strtolower($id));
        if ($this->isIdExisting($id)) {
            $this->db->delete($this->db->get($id));
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function deleteEntries() {
        $this->db->truncate();
    }

    //////////////////////////////////////////////////

    private function itemsToImpl(array $items): array {
        $result = [];
        foreach($items as $item){
            $result[$item->id] = $this->itemToImpl($item);
        }
        return $result;
    }

    private function itemToImpl(Document $item): EntryInterface {
        $attributes = $item->toArray();
        $uniqueName = $attributes['uniqueName'];
        $entry;
        switch ($this->type) {
            case UserInterface::TYPE:
                $entry = new User($uniqueName);
                break;
            case RoleInterface::TYPE:
                $entry = new Role($uniqueName);
                break;
            default:
                throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
        $entry->setAttributes($attributes);
        return $entry;
    }

}