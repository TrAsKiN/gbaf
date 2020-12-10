<?php
namespace GBAF\Controller;

use GBAF\Controller;
use GBAF\Database;
use GBAF\Template;
use GBAF\Template\HomeTemplate;

class HomeController extends Controller
{
    /**
     * @return Template
     */
    public function home(): Template
    {
        $db = new Database();
        $acteurs = $db->handler->query('SELECT * FROM `partner` LIMIT 5;')->fetchAll();
        return (new HomeTemplate())->render($acteurs);
    }
}
