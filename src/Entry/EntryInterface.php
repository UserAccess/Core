<?php

namespace UserAccess\Core\Entry;

interface EntryInterface {

    public function __construct(string $id);

    public function getId(): string;

    public function getDisplayName(): string;

    public function getAttributes(): array;

}