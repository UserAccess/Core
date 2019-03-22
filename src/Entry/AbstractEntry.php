<?php

namespace UserAccess\Core\Entry;

use \UserAccess\Core\UserAccess;
use \UserAccess\Core\Entry\EntryInterface;

abstract class AbstractEntry implements EntryInterface {

    protected $id = '';
    protected $displayName = '';
    protected $description = '';
    protected $readOnly = false;

    public function __construct(string $id) {
        if (empty($id)) {
            throw new \Exception(UserAccess::EXCEPTION_INVALID_ID);
        }
        $id = \trim(\strtoupper($id));
        if(!\preg_match('/^[A-Z0-9_\-]{1,32}/', $id) || \strlen($id) > 32){
            throw new \Exception(UserAccess::EXCEPTION_INVALID_ID);
        }
        $this->id = $id;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getType(): string {
        return $this::TYPE;
    }

    public function getDisplayName(): string {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName) {
        $this->displayName = \trim($displayName);
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description) {
        $this->description = \trim($description);
    }

    public function isReadOnly(): bool {
        return $this->readOnly;
    }

    public function setReadOnly(bool $readOnly) {
        $this->readOnly = $readOnly;
    }

    public function getAttributes(): array {
        return $array = [
            'id' => $this->id,
            'type' => $this::TYPE,
            'displayName' => $this->displayName,
            'description' => $this->description,
            'readOnly' => $this->readOnly
        ];
    }

    public function setAttributes(array $attributes) {
        // id is read only
        // type is read only
        if (array_key_exists('displayName', $attributes)) {
            $this->setDisplayName($attributes['displayName']);
        }
        if (array_key_exists('description', $attributes)) {
            $this->setDescription($attributes['description']);
        }
        if (array_key_exists('readOnly', $attributes)) {
            $this->setReadOnly($attributes['readOnly']);
        }
    }

}