<?php

namespace MVC\Classes;

use MVC\Classes\Model;

class DB extends Model
{

    private static string $tableName;

    public function __construct(string $tableName)
    {
        self::$tableName = $tableName;
    }

    /**
     *  Delete row from database
     * @return bool
     */
    public function delete(): bool
    {

    }

    /**
     *  return first result from the database, if no result then throw exception
     * @return array
     */
    public function firstOrFail(): array
    {

    }

    /**
     *  return result with {id}
     * @param int $id
     * @return array
     */
    public function find(int $id): array
    {

    }

    /**
     *  return first database result
     * @return array
     */
    public function first(): array
    {

    }

    /**
     *  return first result, if it doesn't exist then a new row will be created
     * @return array
     */
    public function firstOrCreate(): array
    {

    }


    /**
     *  return all database results
     * @param array $query
     * @return array
     */
    public function get(array $query): array
    {

    }

}