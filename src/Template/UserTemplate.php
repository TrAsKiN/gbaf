<?php
namespace GBAF\Template;

use GBAF\App;
use GBAF\Template;

class UserTemplate extends Template
{
    /**
     * @param string $page
     * @param mixed|null $data
     * @return void
     */
    public function render($page, $data = null): void
    {
        $body = file_get_contents(App::TEMPLATES_DIRECTORY . '/user.html');

        switch ($page) {
            case 'login':
                $body = preg_replace('/({TITLE})/', 'Connexion', $body);
                $loginTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/login.html');
                if (isset($_SESSION['isConnected'])) {
                    $loginTemplate = preg_replace('/({MESSAGE})/', '<p>Vous êtes connecté !</p>', $loginTemplate);
                } else {
                    $loginTemplate = preg_replace('/({MESSAGE})/', '', $loginTemplate);
                }
                $body = preg_replace('/({CONTENT})/', $loginTemplate, $body);
                break;
            default:
                App::notFound();
        }

        $this->output = preg_replace('/({BODY})/', $body, $this->output);
        
        print_r($this->output);
    }
}
