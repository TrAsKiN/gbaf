<?php
namespace GBAF\Template;

use GBAF\App;
use GBAF\Template;

class PartnerTemplate extends Template
{
    /**
     * @param mixed|null $data
     * @return self
     */
    public function render($data = null): self
    {
        $body = file_get_contents(App::TEMPLATES_DIRECTORY . '/partner/partner.html');

        $body = preg_replace('/({NAME})/', $data['name'], $body);
        $body = preg_replace('/({LOGO})/', $data['logo'], $body);
        $body = preg_replace('/({DESCRIPTION})/', nl2br($data['description']), $body);

        $this->output = preg_replace('/({BODY})/', $body, $this->output);

        return $this;
    }
}
