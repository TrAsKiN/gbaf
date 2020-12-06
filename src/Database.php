<?php
namespace GBAF;

class Database
{
    public $handler = null;

    public function __construct()
    {
        $configFile = dirname(__DIR__) . '/config.php';
        if (file_exists($configFile)) {
            require $configFile;
        } else {
            require $configFile . '.dist';
        }

        try {
            $this->handler = new \PDO('mysql:host=localhost;dbname=' . $dbParams['name'], $dbParams['user'], $dbParams['pass']);
        } catch (\PDOException $e) {
            throw new \Exception('Unable to connect to the database (' . $e->getMessage() . ')');
        }
    }

    public function __destruct()
    {
        $this->handler = null;
    }
}
