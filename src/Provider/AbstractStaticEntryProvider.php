<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\UserAccess;
use \UserAccess\Core\Entry\EntryInterface;
use \UserAccess\Core\Provider\EntryProviderInterface;

abstract class AbstractStaticEntryProvider implements EntryProviderInterface {

    protected $entries = [];
    protected $type;

    public function __construct(string $type) {
        $this->type = $type;
    }

    public function isEntryExisting(string $id): bool {
        $id = \strtoupper($id);
        if (isset($this->entries[$id])) {
            return true;        
        } else {
            return false;
        }
    }

    public function getEntry(string $id): EntryInterface {
        $id = \strtoupper($id);
        if ($this->isEntryExisting($id)) {
            return $this->entries[$id];
        } else {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_NOT_EXIST);
        }
    }

    public function isProviderReadOnly(): bool {
        return true;
    }

    public function createEntry(EntryInterface $entry) {
        $id = $entry->getId();
        if ($this->isEntryExisting($id)) {
            throw new \Exception(UserAccess::EXCEPTION_ENTRY_ALREADY_EXIST);
        } else {
            $entry->setReadOnly($this->isProviderReadOnly());
            $this->entries[$id] = $entry;
        }
    }

    public function getEntries(): array {
        return $this->entries;
    }

    public function findEntries(string $attributeName, string $attributeValue, string $comparisonOperator = UserAccess::COMPARISON_EQUAL): array {
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

    public function updateEntry(EntryInterface $entry) {
        throw new \Exception(UserAccess::EXCEPTION_ENTRY_READONLY);
    }

    public function deleteEntry(string $id) {
        throw new \Exception(UserAccess::EXCEPTION_ENTRY_READONLY);
    }

}