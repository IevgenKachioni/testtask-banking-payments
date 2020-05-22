<?php

namespace Services;

final class DB
{
    /** @var DB */
    private static $instance;

    /**
     * Gets the instance via lazy initialization (created on first usage).
     * @return \PDO
     * @throws \Exception
     */
    public static function getConnection()
    {
        if (null === static::$instance) {

            // Getting the DB configuration data.
            $envData = file_get_contents(__DIR__ . '/../env.json');
            $config = json_decode($envData, true);

            $conn = new \PDO('mysql:host=' . $config['dbhost'] . ';' . 'dbname=' . $config['dbname'],
                $config['dbuser'],
                $config['dbpassword']);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            if (!$conn) {
                throw new \Exception('Cannot connect to the database!');
            }

            static::$instance = $conn;
        }

        return static::$instance;
    }

    /**
     * Prepared statements query.
     *
     * @param string $query
     * @param array $bindings
     * @param boolean $isSelectQuery
     * @return array
     * @throws \Exception
     */
    public static function query($query, $bindings, $isSelectQuery = true)
    {
        $conn = DB::getConnection();

        $stmt = $conn->prepare($query);
        $stmt->execute($bindings);

        // We are only calling 'fetchAll' for selecting, not for inserting.
        if ($isSelectQuery) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        return [];
    }

    /**
     * Is not allowed to call from outside to prevent from creating multiple instances,
     * to use the singleton, you have to obtain the instance from Singleton::getInstance() instead.
     */
    private function __construct()
    {
    }

    /**
     * Prevent the instance from being cloned (which would create a second instance of it).
     */
    private function __clone()
    {
    }

    /**
     * Prevent from being deserialized (which would create a second instance of it)
     */
    private function __wakeup()
    {
    }
}
