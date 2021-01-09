<?php
namespace GBAF\Template;

use DateTime;
use DateTimeZone;
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
        $comments = '';
        foreach ($data['comments'] as $comment) {
            $date = new DateTime($comment['date_add'], new DateTimeZone('Europe/Paris'));
            $commentTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/partner/comment.html');
            $commentTemplate = preg_replace('/({FIRSTNAME})/', $comment['firstname'], $commentTemplate);
            $commentTemplate = preg_replace('/({DATE})/', $date->format('d/m/Y'), $commentTemplate);
            $commentTemplate = preg_replace('/({COMMENT})/', $comment['comment'], $commentTemplate);
            $comments .= $commentTemplate;
        }
        $body = preg_replace('/({DESCRIPTION})/', nl2br($data['partner']['description']), $body);
        $body = preg_replace('/({COMMENTS_NUM})/', count($data['comments']), $body);
        $body = preg_replace('/({GRADES_NUM})/', count($data['grades']), $body);
        $body = preg_replace('/({COMMENTS})/', $comments, $body);

        $this->output = preg_replace('/({BODY})/', $body, $this->output);

        return $this;
    }
}
