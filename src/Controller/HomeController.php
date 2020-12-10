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
        $partners = $db->getPartners();
        return (new HomeTemplate())->render($partners);
    }
}
