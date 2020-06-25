<?php

namespace PragmaPHP\UserAccess\Entry;

use \PragmaPHP\UserAccess\UserAccess;

abstract class AbstractEntry implements EntryInterface {

    protected $id = '';
    protected $uniqueName = '';
    protected $displayName = '';
    protected $description = '';
    protected $readOnly = false;

    public function __construct(string $uniqueName) {
        if (empty($uniqueName)) {
            throw new \Exception(UserAccess::EXCEPTION_INVALID_UNIQUE_NAME);
        }
        $uniqueName = trim(strtolower($uniqueName));
        if(!preg_match('/^[a-z0-9_\-]{1,32}/', $uniqueName) || strlen($uniqueName) > 32){
            throw new \Exception(UserAccess::EXCEPTION_INVALID_UNIQUE_NAME);
        }
        $this->uniqueName = $uniqueName;
    }

    public function getType(): string {
        return $this::TYPE;
    }

    public function getId(): string {
        return $this->id;
    }

    public function setId(string $id) {
        $this->id = trim($id);
    }

    public function getUniqueName(): string {
        return $this->uniqueName;
    }

    public function setUniqueName(string $uniqueName) {
        $this->uniqueName = trim($uniqueName);
    }

    public function getDisplayName(): string {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName) {
        $this->displayName = trim($displayName);
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description) {
        $this->description = trim($description);
    }

    public function isReadOnly(): bool {
        return $this->readOnly;
    }

    public function setReadOnly(bool $readOnly) {
        $this->readOnly = $readOnly;
    }

    public function getAttributes(): array {
        return $array = [
            'type' => $this::TYPE,
            'id' => $this->getId(),
            'uniqueName' => $this->getUniqueName(),
            'displayName' => $this->getDisplayName(),
            'description' => $this->getDescription(),
            'readOnly' => $this->isReadOnly()
        ];
    }

    public function setAttributes(array $attributes) {
        // type is read only
        if (array_key_exists('_id', $attributes)) {
            $this->setId($attributes['_id']);
        }
        // if (array_key_exists('uniqueName', $attributes)) {
        //     $this->setUniqueName($attributes['uniqueName']);
        // }
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