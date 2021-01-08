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

function getPartners() {
    global $handler;
    $query = $handler->prepare('SELECT * FROM `partner`;');
    $query->execute();
    return $query->fetchAll();
}

function getPartner(int $id) {
    global $handler;
    $query = $handler->prepare('SELECT * FROM `partner` WHERE `id` = :id;');
    $query->execute([':id' => $id]);
    return $query->fetch();
}

function getGrades(int $partnerId) {
    global $handler;
    $query = $handler->prepare('SELECT * FROM `grade` WHERE `id_partner` = :partnerId;');
    $query->execute([':partnerId' => $partnerId]);
    return $query->fetchAll();
}

function getComments(int $partnerId) {
    global $handler;
    $query = $handler->prepare('SELECT * FROM `comment` WHERE `id_partner` = :partnerId;');
    $query->execute([':partnerId' => $partnerId]);
    return $query->fetchAll();
}

function getUserByUsername(string $username) {
    global $handler;
    $query = $handler->prepare('SELECT * FROM `user` WHERE `username` = :username;');
    $query->execute([':username' => $username]);
    return $query->fetch();
}

function addUser(array $newUser) {
    global $handler;
    $query = $handler->prepare(
        'INSERT INTO `user` (`lastname`, `firstname`, `username`, `password`, `question`, `answer`)
                VALUES (:lastname, :firstname, :username, :passwd, :question, :answer);'
    );
    return $query->execute([
        ':lastname' => $newUser['lastname'],
        ':firstname' => $newUser['firstname'],
        ':username' => $newUser['username'],
        ':passwd' => $newUser['password'],
        ':question' => $newUser['question'],
        ':answer' => $newUser['answer']
    ]);
}

function updateLastname(string $newLastname) {
    global $handler;
}

function updateFirstname(string $newFirstname) {
    global $handler;
}

function updateQuestion(string $newQuestion) {
    global $handler;
}

function updateAnswer(string $newAnswer) {
    global $handler;
}

function updatePassword(string $newPassword) {
    global $handler;
}
