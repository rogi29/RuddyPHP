<?php

namespace Ruddy\Database\Drivers\PDO;

/**
 * Ruddy Framework Data Access Objects IDriver
 *
 * @author Nick Vlug <nick@ruddy.nl>
 */

interface IPDO
{
    public function getConn();
}
