<?php

namespace PragmaPHP\UserAccess\Provider;

use \PragmaPHP\UserAccess\UserAccess;
use \PragmaPHP\UserAccess\Entry\EntryInterface;

abstract class AbstractStaticEntryProvider implements EntryProviderInterface {

    protected $entries = [];
    protected $type;

    public function __construct(string $type) {
        $this->type = $type;
    }

    public function isIdExisting(string $id): bool {
        $id = trim(strtolower($id));
        if (isset($this->entries[$id])) {
            return true;        
        } else {
            return false;
        }
    }

    public function isUniqueNameExisting(string $uniqueName): bool {
        return $this->isIdExisting($uniqueName);
    }

    public function getEntry(string $id): EntryInterface {
        $id = trim(strtolower($id));
        if ($this->isIdExisting($id)) {
            return $this->entries[$id];
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function isReadOnly(): bool {
        return true;
    }

    public function createEntry(EntryInterface $entry): EntryInterface {
        $uniqueName = $entry->getUniqueName();
        if ($this->isUniqueNameExisting($uniqueName)) {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_ALREADY_EXIST);
        } else {
            $entry->setReadOnly($this->isReadOnly());
            $id = $uniqueName;
            $entry->setId($id);
            $this->entries[$id] = $entry;
            return $entry;
        }
    }

    public function getEntries(): array {
        return $this->entries;
    }

    public function findEntries(string $search_key, string $search_value): array {
        $search_key = trim($search_key);
        $search_value = trim($search_value);
        $result = [];
        foreach($this->entries as $entry){
            $attributes = $entry->getAttributes();
            if (array_key_exists($search_key, $attributes)) {
                if (self::startsWith($search_value, '*') && self::endsWith($search_value, '*') && strlen($search_value) > 3) {
                    if (stripos($attributes[$search_key], substr($search_value, 1, -1)) !== false) {
                        $result[] = $entry;
                    }
                } else {
                    if (strcasecmp($attributes[$search_key], $search_value) === 0) {
                        $result[] = $entry;
                    }
                }
            }
        }
        return $result;
    }

    public function updateEntry(EntryInterface $entry): EntryInterface {
        throw new \Exception(UserAccess::EXCEPTION_ENTRY_READONLY);
    }

    public function deleteEntry(string $uniqueName) {
        throw new \Exception(UserAccess::EXCEPTION_ENTRY_READONLY);
    }

    public function deleteEntries() {
        throw new \Exception(UserAccess::EXCEPTION_ENTRY_READONLY);
    }

    private static function startsWith($haystack, $needle) {
        return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
    }

    private static function endsWith($haystack, $needle) {
        return substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }

}