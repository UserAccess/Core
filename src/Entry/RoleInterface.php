<?php

namespace UserAccess\Core\Entry;

interface RoleInterface extends EntryInterface {

    const TYPE = 'ROLE';
    
    public function getDescription(): string;

}