<?php
namespace GBAF;

use GBAF\App;

class Controller
{
    public $tplDirectory;

    public function __construct()
    {
        $this->tplDirectory = dirname(App::TEMPLATES_DIRECTORY);
    }
}
