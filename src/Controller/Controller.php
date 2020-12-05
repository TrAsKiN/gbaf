<?php
namespace GBAF\Controller;

class Controller
{
    public $tplDirectory;

    /**
     * @param string $tplDirectory The templates directory
     * @return void
     */
    public function __construct($tplDirectory)
    {
        $this->tplDirectory = $tplDirectory;
    }
}
