<?php

namespace Core;

use MVC\Classes\Database\Database;

abstract class Model extends Database
{
    /*
     *  gets all matching information
     */
    abstract public function get();

    /*
     *  if no results it will throw an exception
     */
    abstract public function getOrFail();

    /*
     *  @returns first matching row
     */
    abstract public function first();

    /*
     * try to return a result. If it doesn't exist then create it
     */
    abstract public function firstOrCreate();

    /*
     *  returns first matching row, if no results then it will throw an exception
     */
    abstract public function firstOrFail();

    /*
     *  updates row in database
     */
    abstract public function update();

    /*
     *  updates row in database, if no matching row then it will throw an exception
     */
    abstract public function updateOrFail();
}
