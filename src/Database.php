<?php
namespace GBAF;

class Database
{
    public $handler = null;

    public function __construct()
    {
        if (file_exists(__DIR__ . '/../config.php')) {
            require __DIR__ . '/../config.php';
        } else {
            require __DIR__ . '/../config.php.dist';
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
