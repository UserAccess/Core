<?php

namespace PragmaPHP\UserAccess\Provider;

use \PragmaPHP\UserAccess\UserAccess;
use \PragmaPHP\UserAccess\Entry\EntryInterface;
use \PragmaPHP\UserAccess\Entry\User;
use \PragmaPHP\UserAccess\Entry\UserInterface;
use \PragmaPHP\UserAccess\Entry\Group;
use \PragmaPHP\UserAccess\Entry\GroupInterface;
use \PragmaPHP\UserAccess\Entry\Role;
use \PragmaPHP\UserAccess\Entry\RoleInterface;

use \PragmaPHP\FileDB\FileDB;

abstract class AbstractFileEntryProvider implements EntryProviderInterface {

    protected $db;
    protected $type;

    public function __construct(string $type, string $directory = 'data') {
        $this->type = $type;
        $this->db = new FileDB($directory);
    }

    public function isIdExisting(string $id): bool {
        $id = trim($id);
        return !empty($this->db->read($id));
    }

    public function isUniqueNameExisting(string $uniqueName): bool {
        $uniqueName = trim($uniqueName);
        $entries = $this->db->read(null, [
            'uniqueName' => $uniqueName
        ]);
        return !empty($entries);
    }

    public function isReadOnly(): bool {
        return false;
    }

    public function createEntry(EntryInterface $entry): EntryInterface {
        $uniqueName = $entry->getUniqueName();
        if ($this->isUniqueNameExisting($uniqueName)) {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_ALREADY_EXIST);
        } else {
            $entry->setReadOnly($this->isReadOnly());
            $id = $this->db->create($entry->getAttributes());
            $entry->setId($id);
            return $entry;
        }
    }

    public function getEntry(string $id): EntryInterface {
        $id = trim($id);
        if ($this->isIdExisting($id)) {
            return $this->documentToEntry($this->db->read($id)[0]);
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function getEntries(): array {
        $items = $this->db->readAll();
        return $this->documentsToEntries($items);
    }

    public function findEntries(string $search_key, string $search_value): array {
        $search_key = trim($search_key);
        $search_value = trim($search_value);
        $items = $this->db->read(null, [
            $search_key => $search_value
        ]);
        return $this->documentsToEntries($items);
    }
    
    public function updateEntry(EntryInterface $entry): EntryInterface {
        $id = $entry->getId();
        if ($this->isIdExisting($id)) {
            $this->db->update($id, $entry->getAttributes());
            return $entry;
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function deleteEntry(string $id) {
        $id = trim(strtolower($id));
        if ($this->isIdExisting($id)) {
            $this->db->delete($id);
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function deleteEntries() {
        $this->db->deleteAll();
    }

    //////////////////////////////////////////////////

    private function documentsToEntries(array $items): array {
        $result = [];
        foreach($items as $item){
            $result[] = $this->documentToEntry($item);
        }
        return $result;
    }

    private function documentToEntry(array $attributes): EntryInterface {
        $uniqueName = $attributes['uniqueName'];
        switch ($this->type) {
            case UserInterface::TYPE:
                $entry = new User($uniqueName);
                break;
            case GroupInterface::TYPE:
                $entry = new Group($uniqueName);
                break;
            case RoleInterface::TYPE:
                $entry = new Role($uniqueName);
                break;
            default:
                throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
        $entry->setAttributes($attributes);
        $entry->setId($attributes['_id']);
        return $entry;
    }

}