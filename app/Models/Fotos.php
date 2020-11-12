<?php


namespace App\Models;


class Fotos extends AbstractDBConnection implements Interfaces\Model, \JsonSerializable
{

    protected function save(string $query): ?bool
    {
        // TODO: Implement save() method.
    }

    static function search($query): ?array
    {
        // TODO: Implement search() method.
    }

    static function getAll(): ?array
    {
        // TODO: Implement getAll() method.
    }

    static function searchForId(int $id): ?object
    {
        // TODO: Implement searchForId() method.
    }

    function insert()
    {
        // TODO: Implement insert() method.
    }

    function update()
    {
        // TODO: Implement update() method.
    }

    function deleted()
    {
        // TODO: Implement deleted() method.
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}