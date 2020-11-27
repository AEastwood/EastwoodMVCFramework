<?php

namespace MVC\Classes;

use MVC\App\Exceptions\DatabaseDoesntExistException;

class Database {

    public static \PDO $dbh;

    private bool $exists;
    private string $host;
    private string $username;
    private string $password;
    private string $connection;
    private string $port;
    private string $database_name;
    private string $persistent;
    private string $state;
    private int $timeout;
    private array $table_list;

    /**
     *  constructor
     */
    public function __construct() 
    {
        $this->connection = $_ENV['DATABASE_CONNECTION'];
        $this->exists     = false;
        $this->persistent = $_ENV['DATABASE_PERSIST'] ?? false;
        $this->table_list = [];
        $this->timeout    = $_ENV['DATABASE_TIMEOUT'] ?? 15;

        if($this->databaseParametersDeclared()) {
            
            $this->host          = $_ENV['DATABASE_HOST'];
            $this->username      = $_ENV['DATABASE_USER']; 
            $this->password      = $_ENV['DATABASE_PASS'];
            $this->port          = $_ENV['DATABASE_PORT'];
            $this->database_name = $_ENV['DATABASE_NAME'];
            
            $this->connect();

            if($this->isPersistent() && !$this->databaseExists($this->database_name)) {
                $this->createDatabase();
            }
            
            $this->createTable('users', '
                `id` INT(1) AUTO_INCREMENT PRIMARY_KEY,
                `name` VARCHAR(60) NOT NULL,
                `email` VARCHAR(60) NOT NULL,
                `username` VARCHAR(60) NOT NULL,
                `password` VARCHAR(60) NOT NULL,
                `created_at` DATE NOT NULL,
                `updated_at` DATE NOT NULL,'
            );

            $this->table_list = $this->listTables();
        }
    }

    /**
     *  serialize
     */
    public function __serialize()
    {
        $data = [
            'exists'        => $this->exists,
            'host'          => $this->host,
            'username'      => $this->username,
            'password'      => $this->password,
            'port'          => $this->port,
            'database_name' => $this->database_name,
            'persistent'    => $this->persistent,
            'timeout'       => $this->timeout,
            'state'         => $this->state,
        ];

        return ($data);
    }

    /**
     *  restore database connection
     */
    public function __wakeup()
    {
        $this->connect();
    }

    /**
     *  try to connect to the database
     */
    private function connect(): void
    {
        try {
            $dsn = $this->connection . ":host=" . $this->host. ';dbname=INFORMATION_SCHEMA';

            self::$dbh = new \PDO(
                $dsn, 
                $this->username, 
                $this->password
            );

            self::$dbh->setAttribute(\PDO::ATTR_TIMEOUT, $this->timeout);
            self::$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->state = 'connected';
        }
        catch(PDOEXception $e) {
            throw new DatabaseConnectionError($e);
        }
    }

    /**
     *  create table if it doesn't exist
     *  @returns bool
     */
    private function createDatabase(): bool
    {
        try {
            $stmt = self::$dbh->prepare('CREATE DATABASE IF NOT EXISTS ' . $this->database_name);
            $stmt->execute();
            
            return true;
        }
        catch(PDOException $e) {
            return false;
        }
    }

    /**
     *  create table in database
     */
    public function createTable(string $table_name, string $schema): bool
    {
        if(!$this->databaseExists($this->database_name)){
            throw new DatabaseDoesntExistException($this->database_name);
        }

        if($this->tableExists($table_name)) {
            throw new TableAlreadyExistsException($table_name);
        }

        self::$dbh->prepare('CREATE TABLE ' . $table_name . '(' . $schema . ');');

        self::$dbh->execute();

        return true;
    }
    
    /**
     *  checks if database is delcared in the .env file
     *  @returns bool
     */
    private function databaseParametersDeclared(): bool
    {
        $vars = [
            'DATABASE_CONNECTION',
            'DATABASE_HOST',
            'DATABASE_USER',
            'DATABASE_PASS',
            'DATABASE_NAME',
            'DATABASE_PORT',
        ];

        foreach($vars as $var) {
            if(!isset($_ENV[$var])) {
                return false;
            }
        }
        
        return true;
    }

    /**
     *  check database exists
     *  @var $database_name name of database
     *  @returns bool
     */
    private function databaseExists(string $database_name): bool
    {
        $stmt = self::$dbh->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . $database_name . "'");

        if($stmt->fetchColumn()) {
            $this->exists = true;
            return true;
        }

        return false;
    }

    /**
     *  checks if database is persistant
     *  @ returns bool
     */
    private function isPersistent(): bool
    {
        if($this->persistent) {
            return true;
        }

        return false;
    }

    /**
     *  return list of tables in database
     *  @returns array of table names
     */
    private function listTables(): array
    {
        if($this->state === 'connected') {
            $table_names = self::$dbh->prepare('SHOW TABLES');

            return ($table_names->fetchAll(\PDO::FETCH_COLUMN));
        }

        return [];
    }

    /**
     *  check table exists
     *  @returns bool
     */
    private function tableExists(): bool
    {

    }

}