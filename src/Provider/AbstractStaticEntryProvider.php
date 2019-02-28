<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\UserAccess;
use \UserAccess\Core\Entry\EntryInterface;
use \UserAccess\Core\Provider\EntryProviderInterface;

abstract class AbstractStaticEntryProvider implements EntryProviderInterface {

    protected $entries = [];

    public function __construct() {
    }

    public function isEntryExisting(string $id): bool {
        if (isset($this->entries[$id])) {
            return true;        
        } else {
            return false;
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

    public function updateEntry(EntryInterface $entry) {
        throw new \Exception(UserAccess::EXCEPTION_ENTRY_READONLY);
    }

    public function deleteEntry(string $id) {
        throw new \Exception(UserAccess::EXCEPTION_ENTRY_READONLY);
    }

}