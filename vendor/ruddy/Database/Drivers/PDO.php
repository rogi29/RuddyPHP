<?php

namespace ruddy\Database\Drivers;

/**
 * Ruddy Framework Data Access Objects Driver
 *
 * @author Gil Nimer <gil@ruddy.nl>
 */

class PDO implements IDriver
{
    /**
     * @var bool
     */
    protected $driver = false;

    /**
     * @var bool
     */
    private $_db = false;

    /**
     * @var null
     */
    private $_query = null;

    /**
     * Connect to Database
     *
     * @param $driver
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     * @param null $port
     * @return mixed
     * @throws \Exception
     */
    public function connect($driver, $host, $database, $username, $password, $port = null)
    {
        $driver = "Ruddy\\Database\\Drivers\\PDO\\{$driver}";
        try {
            $this->driver = new $driver($host, $database, $username, $password, $port);
        } catch (\Exception $e){
            die('Error!:'. $e->getMessage());
        }

        return $this->_db = $this->driver->getConn();
    }

    /**
     * Disconnect database
     *
     * @return bool
     */
    public function disconnect()
    {
        $this->driver = false;
        $this->_db = false;
        return true;
    }

    /**
     * Prepare a query
     *
     * @param $query
     * @return mixed
     */
    public function prepare($query)
    {
        return $this->_query = $this->_db->prepare($query);
    }

    /**
     * Bind parameters to a query
     *
     * @param $param
     * @param $value
     * @param null $type
     * @return mixed
     */
    public function bind($param, $value, $type = null)
    {
        if ($type == null) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        return $this->_query->bindValue($param, $value, $type);
    }

    /**
     * Execute query
     *
     * @return mixed
     */
    public function execute()
    {
        return $this->_query->execute();
    }

    /**
     * Close Query
     *
     * @return bool
     */
    public function close()
    {
        $this->_query = null;
        return true;
    }

    /**
     * Fetch a single row
     *
     * @param int $fetch_style
     * @return mixed
     */
    public function fetch($fetch_style = \PDO::FETCH_ASSOC)
    {
        return $this->_query->fetch($fetch_style);
    }

    /**
     * Fetch all rows
     *
     * @param int $fetch_style
     * @return mixed
     */
    public function fetchAll($fetch_style = \PDO::FETCH_ASSOC)
    {
        return $this->_query->fetchAll($fetch_style);
    }

    /**
     * Fetch column
     *
     * @param $column_number
     * @return mixed
     */
    public function fetchColumn($column_number = 0)
    {
        return $this->_query->fetchColumn($column_number);
    }

    /**
     * Fetch as an Object
     *
     * @param $class_name
     * @param null $ctor_args
     * @return mixed
     */
    public function fetchObject($class_name, $ctor_args = null)
    {
        if($ctor_args == null)
            return $this->_query->fetchObject($class_name);

        return $this->_query->fetchObject($class_name, $ctor_args);
    }
} 