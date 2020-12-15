<?php
namespace GBAF\Template;

use GBAF\App;
use GBAF\Template;

class PartnerTemplate extends Template
{
    public function render(?array $data): self
    {
        $body = file_get_contents(App::TEMPLATES_DIRECTORY . '/partner/partner.html');

        $body = preg_replace('/({NAME})/', $data['partner']['name'], $body);
        $body = preg_replace('/({LOGO})/', $data['partner']['logo'], $body);
        if ($data['partner']['website']) {
            $websiteTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/partner/website.html');
            $websiteTemplate = preg_replace('/({URL})/', $data['partner']['website'], $websiteTemplate);
            $body = preg_replace('/({WEBSITE})/', $websiteTemplate, $body);
        }
        $body = preg_replace('/({DESCRIPTION})/', nl2br($data['partner']['description']), $body);
        $body = preg_replace('/({COMMENTS_NUM})/', count($data['comments']), $body);

        $this->output = preg_replace('/({BODY})/', $body, $this->output);

        return $this;
    }
}
