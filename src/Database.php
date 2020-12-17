<?php
namespace GBAF;

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
            $this->handler = new \PDO('mysql:host=localhost;dbname=' . $dbParams['name'] . ';charset=UTF8', $dbParams['user'], $dbParams['pass']);
        } catch (\PDOException $e) {
            error_log('Unable to connect to the database (' . $e->getMessage() . ')');
        }
    }

    public function __destruct()
    {
        $this->handler = null;
    }

    public function getPartners(): array
    {
        $query = $this->handler->prepare('SELECT * FROM `partner`;');
        $query->execute();
        return $query->fetchAll();
    }

    public function getPartner(int $id): array
    {
        $query = $this->handler->prepare('SELECT * FROM `partner` WHERE `id` = :id;');
        $query->execute([':id' => $id]);
        return $query->fetch();
    }

    public function getGrades(int $partnerId): array
    {
        $query = $this->handler->prepare('SELECT * FROM `grade` WHERE `id_partner` = :partnerId;');
        $query->execute([':partnerId' => $partnerId]);
        return $query->fetchAll();
    }

    public function getComments(int $partnerId): array
    {
        $query = $this->handler->prepare('SELECT * FROM `comment` WHERE `id_partner` = :partnerId;');
        $query->execute([':partnerId' => $partnerId]);
        return $query->fetchAll();
    }

    public function getUserByUsername(string $username)
    {
        $query = $this->handler->prepare('SELECT * FROM `user` WHERE `username` = :username;');
        $query->execute([':username' => $username]);
        return $query->fetch();
    }

    public function addUser(array $newUser): bool
    {
        $query = $this->handler->prepare('INSERT INTO `user` (`lastname`, `firstname`, `username`, `password`, `question`, `answer`)
                                            VALUES (:lastname, :firstname, :username, :passwd, :question, :answer);');
        return $query->execute([
            ':lastname' => $newUser['lastname'],
            ':firstname' => $newUser['firstname'],
            ':username' => $newUser['username'],
            ':passwd' => $newUser['password'],
            ':question' => $newUser['question'],
            ':answer' => $newUser['answer']
        ]);
    }

    public function updateLastname(string $newLastname)
    {

    }

    public function updateFirstname(string $newFirstname)
    {

    }

    public function updateQuestion(string $newQuestion)
    {

    }

    public function updateAnswer(string $newAnswer)
    {

    }
}
