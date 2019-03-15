<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\Entry\EntryInterface;

interface EntryProviderInterface {

    public function isEntryExisting(string $id): bool;

    public function isProviderReadOnly(): bool;

    public function createEntry(EntryInterface $entry);

    public function getEntry(string $id): ?EntryInterface;

    public function getEntries(): ?array;

    public function findEntries(string $attributeName, string $attributeValue, string $comparisonOperator): ?array;
    
    public function updateEntry(EntryInterface $entry);

    public function deleteEntry(string $id);

}