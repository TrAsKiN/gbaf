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
        $body = file_get_contents(App::TEMPLATES_DIRECTORY . '/home.html');
        $acteurTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/acteur.html');

        $body = preg_replace('/({TITLE})/', $this->title, $body);

        $acteurs = '';
        foreach($data as $acteur) {
            $acteurOutput = $acteurTemplate;
            $acteurOutput = preg_replace('/({ACTEUR})/', $acteur['acteur'], $acteurOutput);
            $acteurOutput = preg_replace('/({LOGO})/', 'images/' . $acteur['logo'], $acteurOutput);
            $acteurOutput = preg_replace('/({DESCRIPTION})/', nl2br($acteur['description']), $acteurOutput);
            $acteurOutput = preg_replace('/({LINK})/', '/partners/partner-' . $acteur['id_acteur'], $acteurOutput);
            $acteurs .= $acteurOutput;
        }

        if (!empty($acteurs)) {
            $body = preg_replace('/({ACTEURS})/', $acteurs, $body);
        } else {
            $body = preg_replace('/({ACTEURS})/', '<p>Aucun acteur à afficher.</p>', $body);
        }

        $this->output = preg_replace('/({BODY})/', $body, $this->output);
        
        print_r($this->output);
    }
}
