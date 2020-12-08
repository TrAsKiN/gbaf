<?php
namespace GBAF\Template;

use GBAF\App;
use GBAF\Template;

class PartnerTemplate extends Template
{
    /**
     * @param mixed|null $data
     * @return void
     */
    public function render($data = null): void
    {
        $body = file_get_contents(App::TEMPLATES_DIRECTORY . '/partner.html');

        $body = preg_replace('/({TITLE})/', $data['acteur'], $body);

        $this->output = preg_replace('/({BODY})/', $body, $this->output);
        
        print_r($this->output);
    }
}
