<?php
namespace GBAF;

use GBAF\App;

class Controller
{
    public $tplDirectory;

    public function __construct()
    {
        $this->tplDirectory = App::TEMPLATES_DIRECTORY;
    }
}
