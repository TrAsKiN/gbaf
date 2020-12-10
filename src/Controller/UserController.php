<?php
namespace GBAF\Controller;

use GBAF\App;
use GBAF\Controller;
use GBAF\Template;
use GBAF\Template\UserTemplate;

class UserController extends Controller
{
    /**
     * @return Template
     */
    public function login(): Template
    {
        if (!empty($_POST)) {
            $_SESSION['isConnected'] = true;
            $this->addFlash('Vous êtes connecté !');
            App::redirect('/');
        }
        return (new UserTemplate())->render('login');
    }

    /**
     * @return Template
     */
    public function signup(): Template
    {
        if (!empty($_POST)) {
            $_SESSION['isConnected'] = true;
            $this->addFlash('Vous êtes inscrits !');
            App::redirect('/profile');
        }
        return (new UserTemplate())->render('signup');
    }

    /**
     * @return Template
     */
    public function profile(): Template
    {
        if (!empty($_POST)) {
            $this->addFlash('Modifications effectuées !');
        }
        return (new UserTemplate())->render('profile');
    }

    /**
     * @return Template
     */
    public function lostPassword(): Template
    {
        if (!empty($_POST)) {
            $this->addFlash('Nouveau mot de passe enregistré !');
            App::redirect('/login');
        }
        return (new UserTemplate())->render('lost-password');
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        unset($_SESSION['isConnected']);
        App::redirect('/login');
    }
}
