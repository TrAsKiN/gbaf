<?php
namespace GBAF;

class Controller
{
    public $tplDirectory;

    /**
     * @param string $tplDirectory The templates directory
     */
    public function __construct(string $tplDirectory)
    {
        $this->tplDirectory = $tplDirectory;
    }
}
