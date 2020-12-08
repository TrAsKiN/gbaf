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
        $_SESSION['isConnected'] = true;
        (new UserTemplate())->render('login');
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
