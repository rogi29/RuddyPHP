<?php

namespace ruddy\Database;

/**
 * Ruddy Framework Data Access Objects
 *
 * @author Gil Nimer <gil@ruddy.nl>
 */

class Database
{
    /**
     * @var Drivers\IDriver();
     */
    protected $driver = null;

    /**
     * @var array
     */
    protected $drivers = array('MySQL', 'MSSQL', 'SQLSRV', 'PostgreSQL');

    /**
     * Construct class and set driver
     */
    public function __construct()
    {
        $this->driver = new Drivers\PDO();
    }

    /**
     * Connect to Database
     *
     * @param $driver
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     * @param null $port
     * @throws \Exception
     */
    public function connect($driver, $host, $database, $username, $password, $port = null)
    {
        if(is_null($this->driver)) {
            return false;
        }

        if(in_array($driver, $this->drivers)){
            return $this->driver->connect((string)$driver, (string)$host, (string)$database, (string)$username, (string)$password, $port);
        } else {
            throw new \Exception("Database driver does not exists", "500");
        }
    }

    /**
     * Disconnect database
     *
     * @return bool
     */
    public function disconnect()
    {
        if(is_null($this->driver)){
            return false;
        }
        return $this->driver->disconnect();
    }

    /**
     * Prepare a query
     *
     * @param $query
     * @return bool
     */
    public function prepare($query)
    {
        if(is_null($this->driver)){
            return false;
        }
        return $this->driver->prepare((string)$query);
    }

    /**
     * Bind parameters to a query
     *
     * @param $param
     * @param $value
     * @param null $type
     * @return bool
     */
    public function bind($param, $value, $type = null)
    {
        if(is_null($this->driver)){
            return false;
        }
        return $this->driver->bind((string)$param, (string)$value, $type);
    }

    /**
     * Execute query
     *
     * @return bool
     */
    public function execute()
    {
        if(is_null($this->driver)){
            return false;
        }
        return $this->driver->execute();
    }

    /**
     * Close Query
     *
     * @return bool
     */
    public function close()
    {
        if(is_null($this->driver)){
            return false;
        }
        return $this->driver->close();
    }

    /**
     * Fetch a single row
     *
     * @param int $fetch_style
     * @return bool
     */
    public function fetch($fetch_style = \PDO::FETCH_ASSOC)
    {
        if(is_null($this->driver)){
            return false;
        }
        return $this->driver->fetch($fetch_style);
    }

    /**
     * Fetch all rows
     *
     * @param int $fetch_style
     * @return mixed
     */
    public function fetchAll($fetch_style = \PDO::FETCH_ASSOC)
    {
        if(is_null($this->driver)){
            return false;
        }
        return $this->driver->fetchAll($fetch_style);
    }

    /**
     * Fetch column
     *
     * @param $column_number
     * @return bool
     */
    public function fetchColumn($column_number = 0)
    {
        if(is_null($this->driver)){
            return false;
        }
        return $this->driver->fetchColumn((int)$column_number);
    }

    /**
     * Fetch as an Object
     *
     * @param $class_name
     * @param null $ctor_args
     * @return bool
     */
    public function fetchObject($class_name, $ctor_args = null)
    {
        if(is_null($this->driver)){
            return false;
        }
        return $this->driver->fetchObject((string)$class_name, $ctor_args);
    }
} 