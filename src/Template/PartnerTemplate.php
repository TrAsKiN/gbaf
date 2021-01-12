<?php
namespace GBAF\Template;

use GBAF\App;
use GBAF\Template;

class PartnerTemplate extends Template
{
    public function render(?array $data): self
    {
        $gradesUp = [];
        $gradesDown = [];
        $classGradeUp = '';
        $classGradeDown = '';
        foreach ($data['grades'] as $grade) {
            if ($grade['grade']) {
                $gradesUp[] = $grade;
                if ($grade['id_user'] == $data['user']['id']) {
                    $classGradeUp = ' class="grade-selected"';
                }
            } else {
                $gradesDown[] = $grade;
                if ($grade['id_user'] == $data['user']['id']) {
                    $classGradeDown = ' class="grade-selected"';
                }
            }
        }
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
            $date = date_create($comment['date_add']);
            $commentTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/partner/comment.html');
            $commentTemplate = preg_replace('/({FIRSTNAME})/', $comment['firstname'], $commentTemplate);
            $commentTemplate = preg_replace('/({DATE})/', $date->format('d/m/Y'), $commentTemplate);
            $commentTemplate = preg_replace('/({COMMENT})/', $comment['comment'], $commentTemplate);
            $comments .= $commentTemplate;
        }
        $body = preg_replace('/({DESCRIPTION})/', nl2br($data['partner']['description']), $body);
        $body = preg_replace('/({COMMENTS_NUM})/', count($data['comments']), $body);
        $body = preg_replace('/({GRADES_NUM_UP})/', count($gradesUp), $body);
        $body = preg_replace('/({GRADES_NUM_DOWN})/', count($gradesDown), $body);
        $body = preg_replace('/({CLASS_GRADE_UP})/', $classGradeUp, $body);
        $body = preg_replace('/({CLASS_GRADE_DOWN})/', $classGradeDown, $body);
        $body = preg_replace('/({URL_GRADE_UP})/', $data['partner']['id'] . '?grade=1', $body);
        $body = preg_replace('/({URL_GRADE_DOWN})/', $data['partner']['id'] . '?grade=0', $body);
        $body = preg_replace('/({URL_PARTNER})/', '/partner-' . $data['partner']['id'], $body);
        $body = preg_replace('/({COMMENTS})/', $comments, $body);

        $this->output = preg_replace('/({BODY})/', $body, $this->output);

        return $this;
    }
}
