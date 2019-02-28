<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\Entry\EntryInterface;

interface EntryProviderInterface {

    public function isEntryExisting(string $id): bool;

    public function isProviderReadOnly(): bool;

}