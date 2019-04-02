<?php

namespace UserAccess\Entry;

interface EntryInterface {

    public function __construct(string $uniqueName);

    public function getType(): string;

    public function getUniqueName(): string;

    public function getId(): string;

    public function setId(string $id);

    public function getDisplayName(): string;

    public function setDisplayName(string $displayName);

    public function getDescription(): string;

    public function setDescription(string $description);

    public function isReadOnly(): bool;

    public function setReadOnly(bool $readOnly);

    public function getAttributes(): array;

    public function setAttributes(array $attributes);

}