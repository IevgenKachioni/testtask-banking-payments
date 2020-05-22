<?php

namespace App\Commands;

use Services\DB;

abstract class BaseCommand implements CommandInterface
{
    /**
     * @param array $arguments
     */
    public function __construct($arguments)
    {
        $this->setAttributes($arguments);
    }

    /**
     * @return \PDO
     * @throws \Exception
     */
    public function getDBConnection()
    {
        return DB::getConnection();
    }

    /**
     * @param array $params
     * @return self
     */
    public abstract function setAttributes($params);

    /**
     * @return string
     */
    public abstract function run();
}
