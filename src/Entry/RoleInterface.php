<?php

namespace UserAccess\Core\Entry;

interface RoleInterface extends EntryInterface {

    public function getDescription(): string;

}