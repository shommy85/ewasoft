<?php

namespace App\Components\Storage;

interface EntityStorageInterface
{
    public function save($entity): void;

    public function delete($entity): void;

    public function find(string $className, $id);
}