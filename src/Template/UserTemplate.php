<?php
namespace GBAF\Template;

use GBAF\App;
use GBAF\Template;

class UserTemplate extends Template
{
    public function render(string $page, ?array $data = null): self
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
                $profileTemplate = preg_replace('/({LASTNAME})/', $data['lastname'], $profileTemplate);
                $profileTemplate = preg_replace('/({FIRSTNAME})/', $data['firstname'], $profileTemplate);
                $profileTemplate = preg_replace('/({QUESTION})/', $data['question'], $profileTemplate);
                $profileTemplate = preg_replace('/({ANSWER})/', $data['answer'], $profileTemplate);
                $body = preg_replace('/({CONTENT})/', $profileTemplate, $body);
                break;
            case 'check':
                $body = preg_replace('/({TITLE})/', 'Mot de passe perdu', $body);
                $checkTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/user/lost-password/check.html');
                $body = preg_replace('/({CONTENT})/', $checkTemplate, $body);
                break;
            case 'question':
                $body = preg_replace('/({TITLE})/', 'Mot de passe perdu', $body);
                $questionTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/user/lost-password/question.html');
                $questionTemplate = preg_replace('/({USERNAME})/', $data['username'], $questionTemplate);
                $questionTemplate = preg_replace('/({QUESTION})/', $data['question'], $questionTemplate);
                $body = preg_replace('/({CONTENT})/', $questionTemplate, $body);
                break;
            case 'password':
                $body = preg_replace('/({TITLE})/', 'Mot de passe perdu', $body);
                $passwordTemplate = file_get_contents(App::TEMPLATES_DIRECTORY . '/user/lost-password/password.html');
                $passwordTemplate = preg_replace('/({USERNAME})/', $data['username'], $passwordTemplate);
                $body = preg_replace('/({CONTENT})/', $passwordTemplate, $body);
                break;
            default:
                App::notFound();
        }

        $this->output = preg_replace('/({BODY})/', $body, $this->output);

        return $this;
    }
}
