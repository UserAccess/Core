<?php

namespace UserAccess\Core\Entry;

use \UserAccess\Core\Entry\EntryInterface;

abstract class AbstractEntry implements EntryInterface {

    protected $id = '';
    protected $type = '';
    protected $displayName = '';

    public function __construct(string $id) {
        if (empty($id)) {
            throw new \Exception('ID mandatory');
        }
        $this->id = trim(strtolower($id));
    }

    public function getId(): string {
        return $this->id;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getDisplayName(): string {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName) {
        $this->displayName = $displayName;
    }

    public function getAttributes(): array {
        return $array = [
            'id' => $this->id,
            'type' => $this->type,
            'displayName' => $this->displayName
        ];
    }

    public function setAttributes(array $attributes) {
        // id is read only
        // type is read only
        if (!empty($attributes['displayName'])) {
            $this->setDisplayName($attributes['displayName']);
        }
    }

}