<?php
namespace GBAF\Controller;

use GBAF\Controller;
use GBAF\Template;
use GBAF\Template\HomeTemplate;

class HomeController extends Controller
{
    public function home(): Template
    {
        $partners = $this->db->getPartners();
        return (new HomeTemplate())->render($partners);
    }

    public function legals(): Template
    {
        return (new HomeTemplate())->renderHtml('legals');
    }

    public function contact(): Template
    {
        return (new HomeTemplate())->renderHtml('contact');
    }
}
