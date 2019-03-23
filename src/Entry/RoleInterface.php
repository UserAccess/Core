<?php

namespace UserAccess\Entry;

interface RoleInterface extends EntryInterface {

    const TYPE = 'ROLE';
    
    public function getDescription(): string;

    public function setDescription(string $description);

}