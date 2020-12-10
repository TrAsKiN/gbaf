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
        $body = file_get_contents(App::TEMPLATES_DIRECTORY . '/partner/partner.html');

        $body = preg_replace('/({NAME})/', $data['name'], $body);
        $body = preg_replace('/({LOGO})/', $data['logo'], $body);
        $body = preg_replace('/({DESCRIPTION})/', nl2br($data['description']), $body);

        $this->output = preg_replace('/({BODY})/', $body, $this->output);
        
        print_r($this->output);
    }
}
