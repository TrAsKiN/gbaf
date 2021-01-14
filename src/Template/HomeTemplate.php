<?php
namespace GBAF\Template;

use GBAF\App;
use GBAF\Template;

class HomeTemplate extends Template
{
    public function render(?array $data): self
    {
        $body = file_get_contents(App::TEMPLATES_DIRECTORY . '/home/home.html');
        $partnerTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/home/partner.html');

        $body = preg_replace('/({TITLE})/', $this->title, $body);

        $partners = '';
        foreach($data as $partner) {
            $partnerOutput = $partnerTemplate;
            $partnerOutput = preg_replace('/({NAME})/', $partner['name'], $partnerOutput);
            $partnerOutput = preg_replace('/({LOGO})/', 'images/' . $partner['logo'], $partnerOutput);
            if ($partner['website']) {
                $websiteTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/home/website.html');
                $websiteTemplate = preg_replace('/({URL})/', $partner['website'], $websiteTemplate);
                $partnerOutput = preg_replace('/({WEBSITE})/', $websiteTemplate, $partnerOutput);
            }
            $partnerOutput = preg_replace('/({DESCRIPTION})/', nl2br($partner['description']), $partnerOutput);
            $partnerOutput = preg_replace('/({LINK})/', '/partner-' . $partner['id'], $partnerOutput);
            $partners .= $partnerOutput;
        }

        if (!empty($partners)) {
            $body = preg_replace('/({PARTNERS})/', $partners, $body);
        } else {
            $body = preg_replace('/({PARTNERS})/', '<p>Aucun acteur/partenaire à afficher.</p>', $body);
        }

        $this->output = preg_replace('/({BODY})/', $body, $this->output);

        return $this;
    }

    public function renderHtml(string $page): self
    {
        $body = '';
        switch ($page) {
            case 'legals':
                $body = file_get_contents(App::TEMPLATES_DIRECTORY . '/legals.html');
                break;
            case 'contact':
                $body = file_get_contents(App::TEMPLATES_DIRECTORY . '/contact.html');
                break;
        }

        $this->output = preg_replace('/({BODY})/', $body, $this->output);

        return $this;
    }
}
