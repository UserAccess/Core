<?php

namespace UserAccess\Core\Entry;

use \UserAccess\Core\UserAccess;
use \UserAccess\Core\Entry\AbstractEntry;
use \UserAccess\Core\Entry\RoleInterface;

class Role extends AbstractEntry implements RoleInterface {

    protected $type = 'Role';
    private $description = '';

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description) {
        $this->description = trim($description);
    }

    public function getAttributes(): array {
        $attributes = parent::getAttributes();
        $attributes['description'] = $this->description;
        return $attributes;
    }

    public function setAttributes(array $attributes) {
        parent::setAttributes($attributes);
        if (array_key_exists('description', $attributes)) {
            $this->setDescription($attributes['description']);
        }
    }

}