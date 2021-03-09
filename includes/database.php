<?php
$handler = null;

$configFile = dirname(__DIR__) . '/config.php';

if (file_exists($configFile)) {
    require $configFile;
} else {
    require $configFile . '.dist';
}

try {
    $handler = new PDO(
        'mysql:host=localhost;dbname=' . $dbParams['name'] . ';charset=UTF8',
        $dbParams['user'],
        $dbParams['pass']
    );
} catch (PDOException $e) {
    error_log('Unable to connect to the database (' . $e->getMessage() . ')');
}

function getPartners()
{
    global $handler;
    $query = $handler->prepare('SELECT * FROM `partner`;');
    $query->execute();
    return $query->fetchAll();
}

function getPartner(int $id)
{
    global $handler;
    $query = $handler->prepare('SELECT * FROM `partner` WHERE `id` = :id;');
    $query->execute([':id' => $id]);
    return $query->fetch();
}

function getGrades(array $partner)
{
    global $handler;
    $query = $handler->prepare('SELECT * FROM `grade` WHERE `id_partner` = :partnerId;');
    $query->execute([':partnerId' => $partner['id']]);
    return $query->fetchAll();
}

function getComments(array $partner)
{
    global $handler;
    $query = $handler->prepare(
        'SELECT * FROM `comment`
            LEFT JOIN `user` ON `comment`.`id_user` = `user`.`id`
            WHERE `id_partner` = :partnerId;'
    );
    $query->execute([':partnerId' => $partner['id']]);
    return $query->fetchAll();
}

function getUserByUsername(string $username)
{
    global $handler;
    $query = $handler->prepare('SELECT * FROM `user` WHERE `username` = :username;');
    $query->execute([':username' => $username]);
    return $query->fetch();
}

function getGradeByUser(array $partner, array $user)
{
    global $handler;
    $query = $handler->prepare(
        'SELECT * FROM `grade` WHERE `id_partner` = :partner AND `id_user` = :user;'
    );
    $query->execute([
        ':partner' => $partner['id'],
        ':user' => $user['id']
    ]);
    return $query->fetch();
}

function getCommentByUser(array $partner, array $user)
{
    global $handler;
    $query = $handler->prepare(
        'SELECT * FROM `comment` WHERE `id_partner` = :partner AND `id_user` = :user;'
    );
    $query->execute([
        ':partner' => $partner['id'],
        ':user' => $user['id']
    ]);
    return $query->fetch();
}

function addUser(array $newUser)
{
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

function addGrade(int $grade, array $partner, array $user)
{
    global $handler;
    $query = $handler->prepare(
        'INSERT INTO `grade` (id_user, id_partner, grade)
            VALUES (:user, :partner, :grade);'
    );
    return $query->execute([
        ':user' => $user['id'],
        ':partner' => $partner['id'],
        ':grade' => $grade
    ]);
}

function addComment(string $comment, array $partner, array $user)
{
    global $handler;
    $query = $handler->prepare(
        'INSERT INTO `comment` (`id_user`, `id_partner`, `comment`)
            VALUES (:user, :partner, :comment);'
    );
    return $query->execute([
        ':user' => $user['id'],
        ':partner' => $partner['id'],
        ':comment' => $comment
    ]);
}

function updateLastname(string $newLastname, array $user)
{
    global $handler;
    $query = $handler->prepare('UPDATE `user` SET `lastname` = :lastname WHERE `id` = :id;');
    return $query->execute([
        ':lastname' => $newLastname,
        ':id' => $user['id']
    ]);
}

function updateFirstname(string $newFirstname, array $user)
{
    global $handler;
    $query = $handler->prepare('UPDATE `user` SET `firstname` = :firstname WHERE `id` = :id;');
    return $query->execute([
        ':firstname' => $newFirstname,
        ':id' => $user['id']
    ]);
}

function updateQuestion(string $newQuestion, array $user)
{
    global $handler;
    $query = $handler->prepare('UPDATE `user` SET `question` = :question WHERE `id` = :id;');
    return $query->execute([
        ':question' => $newQuestion,
        ':id' => $user['id']
    ]);
}

function updateAnswer(string $newAnswer, array $user)
{
    global $handler;
    $query = $handler->prepare('UPDATE `user` SET `answer` = :answer WHERE `id` = :id;');
    return $query->execute([
        ':answer' => $newAnswer,
        ':id' => $user['id']
    ]);
}

function updatePassword(string $newPassword, array $user)
{
    global $handler;
    $query = $handler->prepare('UPDATE `user` SET `password` = :password WHERE `id` = :id;');
    return $query->execute([
        ':password' => $newPassword,
        ':id' => $user['id']
    ]);
}

function updateGrade(int $grade, array $user)
{
    global $handler;
    $query = $handler->prepare('UPDATE `grade` SET `grade` = :grade WHERE `id_user` = :id;');
    return $query->execute([
        ':grade' => $grade,
        ':id' => $user['id']
    ]);
}
