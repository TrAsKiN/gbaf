<?php
namespace GBAF;

use GBAF\Database;

class Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    protected function addFlash(string $message): void
    {
        $_SESSION['flashMessages'][] = $message;
    }
}
