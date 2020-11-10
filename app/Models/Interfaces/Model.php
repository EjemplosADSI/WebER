<?php

namespace App\Models\Interfaces;
use JsonSerializable;

interface Model
{
    # métodos abstractos para ABM de clases que hereden
    static function search($query): ?array;
    static function getAll(): ?array;
    static function searchForId(int $id): ?object;
    function insert();
    function update();
    function deleted();
}