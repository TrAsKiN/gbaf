<?php
namespace GBAF\Controller;

class Home
{
    private $tplDirectory;

    /**
     * @param string $tplDirectory The templates directory
     * @return void
     */
    public function __construct($tplDirectory)
    {
        $this->tplDirectory = $tplDirectory;
    }

    /**
     * @return void
     */
    public function action(): void
    {
        require $this->tplDirectory . '/home.php';
    }
}
