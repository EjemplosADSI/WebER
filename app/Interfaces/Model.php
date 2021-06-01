<?php

namespace App\Interfaces;

use JsonSerializable;

interface Model extends JsonSerializable
{
    # métodos abstractos para ABM de clases que hereden
    function insert(): ?bool;
    function update(): ?bool;
    function deleted(): ?bool;
    static function search($query): ?array;
    static function searchForId(int $id): ?object;
    static function getAll(): ?array;
}