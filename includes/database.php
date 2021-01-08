<?php
$handler = null;

$configFile = dirname(__DIR__) . '/config.php';

if (file_exists($configFile)) {
    require $configFile;
} else {
    require $configFile . '.dist';
}

try {
    $handler = new PDO('mysql:host=localhost;dbname=' . $dbParams['name'] . ';charset=UTF8', $dbParams['user'], $dbParams['pass']);
} catch (PDOException $e) {
    error_log('Unable to connect to the database (' . $e->getMessage() . ')');
}

function getPartners(): array {
    global $handler;
    $query = $handler->prepare('SELECT * FROM `partner`;');
    $query->execute();
    return $query->fetchAll();
}
