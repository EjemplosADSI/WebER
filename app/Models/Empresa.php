<?php


namespace App\Models;


use App\Interfaces\Model;

class Empresa implements Model
{

    function insert(): ?bool
    {
        // TODO: Implement insert() method.
    }

    function update(): ?bool
    {
        // TODO: Implement update() method.
    }

    function deleted(): ?bool
    {
        // TODO: Implement deleted() method.
    }

    static function search($query): ?array
    {
        // TODO: Implement search() method.
    }

    static function searchForId(int $id): ?object
    {
        // TODO: Implement searchForId() method.
    }

    static function getAll(): ?array
    {
        // TODO: Implement getAll() method.
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}