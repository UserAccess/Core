<?php

namespace UserAccess\Core\Entry;

interface EntryInterface {

    public function __construct(string $id);

    public function getId(): string;

    public function getDisplayName(): string;

    public function setDisplayName(string $id);

    public function getDescription(): string;

    public function setDescription(string $description);

    public function isReadOnly(): bool;

    public function setReadOnly(bool $readOnly);

    public function getAttributes(): array;

    public function setAttributes(array $attributes);

}