<?php
namespace GBAF;

use PDO;
use PDOException;

class Database
{
    protected $handler = null;

    public function __construct()
    {
        $configFile = dirname(__DIR__) . '/config.php';
        if (file_exists($configFile)) {
            require $configFile;
        } else {
            require $configFile . '.dist';
        }

        try {
            $this->handler = new PDO(
                'mysql:host=localhost;dbname=' . $dbParams['name'] . ';charset=UTF8',
                $dbParams['user'],
                $dbParams['pass']
            );
        } catch (PDOException $e) {
            error_log('Unable to connect to the database (' . $e->getMessage() . ')');
        }
    }

    public function __destruct()
    {
        $this->handler = null;
    }

    public function getPartners()
    {
        $query = $this->handler->prepare('SELECT * FROM `partner`;');
        $query->execute();
        return $query->fetchAll();
    }

    public function getPartner(int $id)
    {
        $query = $this->handler->prepare('SELECT * FROM `partner` WHERE `partner`.`id` = :id;');
        $query->execute([':id' => $id]);
        return $query->fetch();
    }

    public function getGrades(int $partnerId)
    {
        $query = $this->handler->prepare('SELECT * FROM `grade` WHERE `id_partner` = :partnerId;');
        $query->execute([':partnerId' => $partnerId]);
        return $query->fetchAll();
    }

    public function getComments(int $partnerId)
    {
        $query = $this->handler->prepare(
            'SELECT * FROM `comment`
            LEFT JOIN `user` ON `comment`.`id_user` = `user`.`id`
            WHERE `id_partner` = :partnerId;'
        );
        $query->execute([':partnerId' => $partnerId]);
        return $query->fetchAll();
    }

    public function getUserByUsername(string $username)
    {
        $query = $this->handler->prepare('SELECT * FROM `user` WHERE `username` = :username;');
        $query->execute([':username' => $username]);
        return $query->fetch();
    }

    public function getGradeByUser(array $partner, array $user)
    {
        $query = $this->handler->prepare(
            'SELECT * FROM `grade` WHERE `id_partner` = :partner AND `id_user` = :user;'
        );
        $query->execute([
            ':partner' => $partner['id'],
            ':user' => $user['id']
        ]);
        return $query->fetch();
    }

    public function addUser(array $newUser): bool
    {
        $query = $this->handler->prepare(
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

    public function addGrade(int $grade, array $partner, array $user): bool
    {
        $query = $this->handler->prepare(
            'INSERT INTO `grade` (id_user, id_partner, grade)
            VALUES (:user, :partner, :grade);'
        );
        return $query->execute([
            ':user' => $user['id'],
            ':partner' => $partner['id'],
            ':grade' => $grade
        ]);
    }

    public function addComment(string $comment, array $partner, array $user): bool
    {
        $query = $this->handler->prepare(
            'INSERT INTO `comment` (`id_user`, `id_partner`, `comment`)
            VALUES (:user, :partner, :comment);'
        );
        return $query->execute([
            ':user' => $user['id'],
            ':partner' => $partner['id'],
            ':comment' => $comment
        ]);
    }

    public function updateLastname(string $newLastname, array $user): bool
    {
        $query = $this->handler->prepare('UPDATE `user` SET `lastname` = :lastname WHERE `id` = :id;');
        return $query->execute([
            ':lastname' => $newLastname,
            ':id' => $user['id']
        ]);
    }

    public function updateFirstname(string $newFirstname, array $user): bool
    {
        $query = $this->handler->prepare('UPDATE `user` SET `firstname` = :firstname WHERE `id` = :id;');
        return $query->execute([
            ':firstname' => $newFirstname,
            ':id' => $user['id']
        ]);
    }

    public function updateQuestion(string $newQuestion, array $user): bool
    {
        $query = $this->handler->prepare('UPDATE `user` SET `question` = :question WHERE `id` = :id;');
        return $query->execute([
            ':question' => $newQuestion,
            ':id' => $user['id']
        ]);
    }

    public function updateAnswer(string $newAnswer, array $user): bool
    {
        $query = $this->handler->prepare('UPDATE `user` SET `answer` = :answer WHERE `id` = :id;');
        return $query->execute([
            ':answer' => $newAnswer,
            ':id' => $user['id']
        ]);
    }

    public function updatePassword(string $newPassword, array $user): bool
    {
        $query = $this->handler->prepare('UPDATE `user` SET `password` = :password WHERE `id` = :id;');
        return $query->execute([
            ':password' => $newPassword,
            ':id' => $user['id']
        ]);
    }

    public function updateGrade(int $grade, array $user): bool
    {
        $query = $this->handler->prepare('UPDATE `grade` SET `grade` = :grade WHERE `id_user` = :id;');
        return $query->execute([
            ':grade' => $grade,
            ':id' => $user['id']
        ]);
    }
}
