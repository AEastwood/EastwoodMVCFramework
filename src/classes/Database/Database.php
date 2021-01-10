<?php

namespace MVC\Classes;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PDOException;
use MVC\App\Exceptions\DatabaseDoesntExistException;

class Database {

    public static \PDO $dbh;

    private string $databaseName;
    private bool $isAvailable;
    private bool $exists;
    private string $host;
    private string $username;
    private string $password;
    private string $connection;
    private int $port;
    private bool $isPersistent;
    private string $state;
    private int $timeout;
    private array $tableList;
    private bool $usersExists;
    private Logger $logger;

    /**
     *  constructor
     */
    public function __construct() 
    {
        $this->logger = new Logger('Database');
        $this->logger->pushHandler(new StreamHandler('../storage/logs/database.log', Logger::WARNING));

        $this->isAvailable  = true;
        $this->connection   = $_ENV['DATABASE_CONNECTION'];
        $this->exists       = false;
        $this->isPersistent = $_ENV['DATABASE_PERSIST'] === 'true';
        $this->tableList    = [];
        $this->timeout      = $_ENV['DATABASE_TIMEOUT'] ?? 15;

        if($this->hasRequiredDatabaseParametersDeclared()) {
            
            $this->host          = $_ENV['DATABASE_HOST'];
            $this->username      = $_ENV['DATABASE_USER']; 
            $this->password      = $_ENV['DATABASE_PASS'];
            $this->port          = $_ENV['DATABASE_PORT'];
            $this->databaseName  = $_ENV['DATABASE_NAME'];
            
            if(!$this->connect()) {
                return;
            }

            if($this->state === 'connected' && $this->isPersistent && !$this->databaseExists($this->databaseName)) {
                $this->createDatabase();
            }

            $this->tableList = $this->listTables();
            $this->usersExists = $this->tableExists('users');
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
            'database_name' => $this->databaseName,
            'persistent'    => $this->isPersistent,
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
    private function connect(): bool
    {
        try {
            $dsn = $this->connection . ":host=" . $this->host. ';dbname=' . $this->databaseName;

            self::$dbh = new \PDO(
                $dsn, 
                $this->username, 
                $this->password
            );

            self::$dbh->setAttribute(\PDO::ATTR_TIMEOUT, $this->timeout);
            self::$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->state = 'connected';

            return true;
        }
        catch(PDOEXception $e) {
            App::body()->logger->error('Unable to connect to database, setting database availability to false. Error: ' . $e->getMessage());
            $this->isAvailable = false;

            return false;
        }
    }

    /**
     *  create table if it doesn't exist
     *  @returns bool
     */
    private function createDatabase(): bool
    {
        try {
            $stmt = self::$dbh->prepare('CREATE DATABASE IF NOT EXISTS ' . $this->databaseName);
            $stmt->execute();
            
            return true;
        }
        catch(PDOException $e) {
            $this->logger->error('Unable to create database "' . $this->databaseName . '", Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     *  create table in database
     * @param string $tableName
     * @param string $schema
     * @return bool
     * @throws DatabaseDoesntExistException
     */
    private function createTable(string $tableName, string $schema = ''): bool
    {
        if(!$this->databaseExists($this->databaseName)){
            $this->logger->error('Unable to create table "' . $tableName . '" as database "' . $this->databaseName . '" does not exist.');
            return false;
        }

        if($this->tableExists($tableName)) {
            $this->logger->info('Attempted to create table "' . $tableName . '" but it already exists.');
            return false;
        }

        self::$dbh->prepare('CREATE TABLE ' . $tableName . '(' . $schema . ');');
        self::$dbh->execute();

        return true;
    }

    /**
     *  check database exists
     * @param string $database_name
     * @return bool
     */
    private function databaseExists(string $database_name): bool
    {
        try {
            $stmt = self::$dbh->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . $database_name . "'");

            if($stmt->fetchColumn()) {
                $this->exists = true;
                return true;
            }

            return false;
        }
        catch (PDOException $e) {
            $this->logger->error('Unable to check if database exists, Error: ' . $e->getMessage());
        }
    }

    /**
     *  checks if database is declared in the .env file
     *  @returns bool
     */
    private function hasRequiredDatabaseParametersDeclared(): bool
    {
        if(!isset($_ENV['DATABASE_CONNECTION'], $_ENV['DATABASE_HOST'], $_ENV['DATABASE_USER'], $_ENV['DATABASE_PASS'], $_ENV['DATABASE_NAME'], $_ENV['DATABASE_PORT'])) {
            return false;
        }

        return true;
    }

    /**
     *  return list of tables in database
     *  @returns array of table names
     */
    private function listTables(): array
    {
        if($this->state === 'connected') {
            $tableNames = self::$dbh->prepare('SHOW TABLES');
            $tableNames->execute();
            $tableNames = $tableNames->fetchAll(\PDO::FETCH_COLUMN);

            return ($tableNames);
        }

        $this->logger->info('No tables found in database: "' . $this->databaseName . '"');
        return [];
    }

    /**
     *  check table exists
     * @param string $tableName
     * @return bool
     */
    private function tableExists(string $tableName): bool
    {
        if($this->state === 'connected' && in_array($tableName, $this->tableList)) {
            return true;
        }

        return false;
    }

}
