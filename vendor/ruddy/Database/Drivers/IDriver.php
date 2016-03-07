<?php

namespace ruddy\Database\Drivers;

/**
 * Ruddy Framework Data Access Objects IDriver
 *
 * @author Nick Vlug <nick@ruddy.nl>
 */

interface IDriver
{
    public function connect($host, $database, $username, $password, $port);
    public function disconnect();
    public function prepare($query);
    public function bind($param, $value, $type);
    public function execute();
    public function close();
    public function fetch($fetch_style);
    public function fetchAll($fetch_style);
    public function fetchColumn($column_number);
    public function fetchObject($class_name, $ctor_args);
}
