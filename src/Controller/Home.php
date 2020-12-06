<?php
namespace GBAF\Controller;

use GBAF\Controller;
use GBAF\Database;

class Home extends Controller
{
    /**
     * @return void
     */
    public function action(): void
    {
        new Database();
        require $this->tplDirectory . '/home.php';
    }
}
