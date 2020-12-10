<?php
namespace GBAF\Controller;

use GBAF\App;
use GBAF\Controller;
use GBAF\Template\UserTemplate;

class UserController extends Controller
{
    /**
     * @return void
     */
    public function login(): void
    {
        if (!empty($_POST)) {
            $_SESSION['isConnected'] = true;
            $this->addFlash('Vous êtes connecté !');
            App::redirect('/');
        }
        (new UserTemplate())->render('login');
    }

    /**
     * @return void
     */
    public function signup(): void
    {
        if (!empty($_POST)) {
            $_SESSION['isConnected'] = true;
            $this->addFlash('Vous êtes inscrits !');
            App::redirect('/profile');
        }
        (new UserTemplate())->render('signup');
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        unset($_SESSION['isConnected']);
        App::redirect($_SERVER['HTTP_REFERER']);
    }
}
