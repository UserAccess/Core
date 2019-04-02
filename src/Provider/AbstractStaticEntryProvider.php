<?php

namespace UserAccess\Provider;

use \UserAccess\UserAccess;
use \UserAccess\Entry\EntryInterface;

abstract class AbstractStaticEntryProvider implements EntryProviderInterface {

    protected $entries = [];
    protected $type;

    public function __construct(string $type) {
        $this->type = $type;
    }

    public function isIdExisting(string $id): bool {
        $id = \trim(\strtolower($id));
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
        $id = \trim(\strtolower($id));
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

    public function findEntries(string $attributeName, string $attributeValue, string $comparisonOperator = UserAccess::COMPARISON_EQUAL_IGNORE_CASE): array {
        $attributeName = \trim($attributeName);
        $attributeValue = \trim($attributeValue);
        $result = [];
        foreach($this->entries as $entry){
            $attributes = $entry->getAttributes();
            if (array_key_exists($attributeName, $attributes)) {
                switch ($comparisonOperator) {
                    case UserAccess::COMPARISON_EQUAL:
                        if (strcmp($attributes[$attributeName], $attributeValue) == 0) {
                            $result[] = $entry;
                        }
                        break;
                    case UserAccess::COMPARISON_EQUAL_IGNORE_CASE:
                        if (strcasecmp($attributes[$attributeName], $attributeValue) == 0) {
                            $result[] = $entry;
                        }
                        break;
                    case UserAccess::COMPARISON_LIKE:
                        if (stripos($attributes[$attributeName], $attributeValue) !== false) {
                            $result[] = $entry;
                        }
                        break;
                    default:
                        if (strcasecmp($attributes[$attributeName], $attributeValue) == 0) {
                            $result[] = $entry;
                        }
                        break;
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

}