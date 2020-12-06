<?php
namespace GBAF;

class Template
{
    public $title = 'Groupement Banque-Assurance Français';
    protected $output = '';

    /**
     * @param string $title
     */
    public function __construct($title = null)
    {
        $this->output = file_get_contents(App::TEMPLATES_DIRECTORY . '/main.html');

        if ($title) {
            $this->output = preg_replace('/({TITLE})/', $title, $this->output);
        } else {
            $this->output = preg_replace('/({TITLE})/', $this->title, $this->output);
        }
    }
}
