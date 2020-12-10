<?php
namespace GBAF\Template;

use GBAF\App;
use GBAF\Template;

class UserTemplate extends Template
{
    /**
     * @param string $page
     * @param mixed|null $data
     * @return self
     */
    public function render($page, $data = null): self
    {
        $body = file_get_contents(App::TEMPLATES_DIRECTORY . '/user/user.html');

        switch ($page) {
            case 'login':
                $body = preg_replace('/({TITLE})/', 'Connexion', $body);
                $loginTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/user/login.html');
                $body = preg_replace('/({CONTENT})/', $loginTemplate, $body);
                break;
            case 'signup':
                $body = preg_replace('/({TITLE})/', 'Inscription', $body);
                $signupTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/user/signup.html');
                $body = preg_replace('/({CONTENT})/', $signupTemplate, $body);
                break;
            case 'profile':
                $body = preg_replace('/({TITLE})/', 'Profil', $body);
                $profileTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/user/profile.html');
                $body = preg_replace('/({CONTENT})/', $profileTemplate, $body);
                break;
            case 'lost-password':
                $body = preg_replace('/({TITLE})/', 'Mot de passe perdu', $body);
                $lostPasswordTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/user/lost-password.html');
                $body = preg_replace('/({CONTENT})/', $lostPasswordTemplate, $body);
                break;
            default:
                App::notFound();
        }

        $this->output = preg_replace('/({BODY})/', $body, $this->output);

        return $this;
    }
}
