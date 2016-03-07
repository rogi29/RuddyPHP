<?php

namespace Ruddy\Database\Drivers\PDO;

/**
 * Ruddy Framework Data Access Objects Driver
 *
 * @author Gil Nimer <gil@ruddy.nl>
 */

class SQLSRV implements IPDO
{
    private $_conn = null;

    public function __construct($host, $database, $username, $password, $port = null)
    {
        $strPort = ($port != null) ? "port={$port};" : '';
        try {
            $this->_conn = new \PDO("sqlsrv:Server={$host};{$strPort}Database={$database}", $username, $password);
            $this->_conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            die("Error!: " . $e->getMessage() . "<br/>");
        }
    }

    public function getConn()
    {
        return $this->_conn;
    }
} 