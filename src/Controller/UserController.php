<?php
namespace GBAF\Controller;

use GBAF\App;
use GBAF\Controller;
use GBAF\Template;
use GBAF\Template\UserTemplate;

class UserController extends Controller
{
    public function login(): Template
    {
        if (!empty($_POST)) {
            $securedPost = array_map('htmlspecialchars', $_POST);
            if ($user = $this->db->getUserByUsername($securedPost['username'])) {
                if (password_verify($securedPost['password'], $user['password'])) {
                    $_SESSION['username'] = $securedPost['username'];
                    $_SESSION['isConnected'] = true;
                    $this->addFlash('Vous êtes connecté !');
                    App::redirect('/');
                }
            }
            $this->addFlash("Il y a une erreur avec votre identifiant ou votre mot de passe !");
        }
        return (new UserTemplate())->render('login');
    }

    public function signup(): Template
    {
        if (!empty($_POST)) {
            $securedPost = array_map('htmlspecialchars', $_POST);
            if ($securedPost['password'] == $securedPost['password-confirm']) {
                if (!$this->db->getUserByUsername($securedPost['username'])) {
                    $this->db->addUser([
                        'lastname' => $securedPost['lastname'],
                        'firstname' => $securedPost['firstname'],
                        'username' => $securedPost['username'],
                        'password' => password_hash($securedPost['password'], PASSWORD_DEFAULT),
                        'question' => $securedPost['question'],
                        'answer' => $securedPost['answer'],
                    ]);
                    $_SESSION['username'] = $securedPost['username'];
                    $_SESSION['isConnected'] = true;
                    $this->addFlash("Inscription effectué !");
                    App::redirect('/profile');
                } else {
                    $this->addFlash("L'identifiant existe déjà !");
                }
            } else {
                $this->addFlash("Les mots de passe doivent être identique !");
            }
        }
        return (new UserTemplate())->render('signup');
    }

    public function profile(): Template
    {
        $user = $this->db->getUserByUsername($_SESSION['username']);
        if (!empty($_POST)) {
            $securedPost = array_map('htmlspecialchars', $_POST);
            if (password_verify($securedPost['password'], $user['password'])) {
                $this->addFlash("Modifications effectuées !");
                App::redirect('/profile');
            } else {
                $this->addFlash("Le mot de passe est incorrect !");
            }
        }
        return (new UserTemplate())->render('profile', $user);
    }

    public function lostPassword(): Template
    {
        if (!empty($_POST)) {
            $securedPost = array_map('htmlspecialchars', $_POST);
            $user = $this->db->getUserByUsername($securedPost['username']);
            if ($user) {
                if (isset($securedPost['answer']) && $user['answer'] == $securedPost['answer']) {
                    return (new UserTemplate())->render('password', $user);
                }
                if (isset($securedPost['new-password']) && isset($securedPost['new-password-confirm'])) {
                    if ($securedPost['new-password'] == $securedPost['new-password-confirm']) {
                         $this->addFlash("Nouveau mot de passe enregistré !");
                        App::redirect('/login');
                    }
                }
                return (new UserTemplate())->render('question', $user);
            }
        }
        return (new UserTemplate())->render('check');
    }

    public function logout(): void
    {
        unset($_SESSION['isConnected']);
        unset($_SESSION['username']);
        App::redirect('/login');
    }
}
