<?php

namespace UserAccess\Core\Provider;

use \UserAccess\Core\Entry\EntryInterface;

interface EntryProviderInterface {

    public function isExisting(string $id): bool;

}