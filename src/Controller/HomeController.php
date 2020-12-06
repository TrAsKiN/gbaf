<?php
namespace GBAF\Controller;

use GBAF\Controller;
use GBAF\Database;
use GBAF\Template\HomeTemplate;

class HomeController extends Controller
{
    /**
     * @return void
     */
    public function action(): void
    {
        $db = new Database();
        $acteurs = $db->handler->query('SELECT * FROM `acteur` LIMIT 5;')->fetchAll();
        (new HomeTemplate())->render($acteurs);
    }
}
