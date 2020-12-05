<?php
namespace GBAF\Controller;

use GBAF\Controller\Controller;

class Home extends Controller
{
    /**
     * @return void
     */
    public function action(): void
    {
        require $this->tplDirectory . '/home.php';
    }
}
