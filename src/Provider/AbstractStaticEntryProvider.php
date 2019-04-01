<?php

namespace UserAccess\Provider;

use \UserAccess\UserAccess;
use \UserAccess\Entry\EntryInterface;
use \UserAccess\Provider\EntryProviderInterface;

abstract class AbstractStaticEntryProvider implements EntryProviderInterface {

    protected $entries = [];
    protected $type;

    public function __construct(string $type) {
        $this->type = $type;
    }

    public function isIdExisting(string $id): bool {
        return $this->isUniqueNameExisting($id);
    }

    public function isUniqueNameExisting(string $uniqueName): bool {
        $uniqueName = \trim(\strtolower($uniqueName));
        if (isset($this->entries[$uniqueName])) {
            return true;        
        } else {
            return false;
        }
    }

    public function getEntry(string $uniqueName): EntryInterface {
        $uniqueName = \trim(\strtolower($uniqueName));
        if ($this->isUniqueNameExisting($uniqueName)) {
            return $this->entries[$uniqueName];
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
            $entry->setId($uniqueName);
            $this->entries[$uniqueName] = $entry;
            return $entry;
        }
    }

    public function getEntries(): array {
        return $this->entries;
    }

    public function findEntries(string $attributeName, string $attributeValue, string $comparisonOperator = UserAccess::COMPARISON_EQUAL): array {
        $attributeValue = \trim($attributeValue);
        $result = [];
        foreach($this->entries as $entry){
            $attributes = $entry->getAttributes();
            if (array_key_exists($attributeName, $attributes)) {
                switch ($comparisonOperator) {
                    case UserAccess::COMPARISON_EQUAL:
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