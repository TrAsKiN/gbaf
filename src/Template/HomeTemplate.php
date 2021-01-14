<?php
namespace GBAF\Template;

use GBAF\App;
use GBAF\Template;

class HomeTemplate extends Template
{
    /**
     * @param mixed|null $data
     * @return void
     */
    public function render($data = null): void
    {
        $body = file_get_contents(App::TEMPLATES_DIRECTORY . '/home/home.html');
        $partnerTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/home/partner.html');

        $body = preg_replace('/({TITLE})/', $this->title, $body);

        $partners = '';
        foreach($data as $partner) {
            $partnerOutput = $partnerTemplate;
            $partnerOutput = preg_replace('/({NAME})/', $partner['name'], $partnerOutput);
            $partnerOutput = preg_replace('/({LOGO})/', 'images/' . $partner['logo'], $partnerOutput);
            $partnerOutput = preg_replace('/({DESCRIPTION})/', nl2br($partner['description']), $partnerOutput);
            $partnerOutput = preg_replace('/({LINK})/', '/partner-' . $partner['id'], $partnerOutput);
            $partners .= $partnerOutput;
        }

        if (!empty($partners)) {
            $body = preg_replace('/({PARTNERS})/', $partners, $body);
        } else {
            $body = preg_replace('/({PARTNERS})/', '<p>Aucun acteur à afficher.</p>', $body);
        }

        $this->output = preg_replace('/({BODY})/', $body, $this->output);
        
        print_r($this->output);
    }
}
