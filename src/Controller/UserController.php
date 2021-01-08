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
                if (!empty($securedPost['lastname']) && $securedPost['lastname'] != $user['lastname']) {
                    $this->db->updateLastname($securedPost['lastname'], $user);
                }
                if (!empty($securedPost['firstname']) && $securedPost['firstname'] != $user['firstname']) {
                    $this->db->updateFirstname($securedPost['firstname'], $user);
                }
                if (!empty($securedPost['question']) && $securedPost['question'] != $user['question']) {
                    $this->db->updateQuestion($securedPost['question'], $user);
                }
                if (!empty($securedPost['answer']) && $securedPost['answer'] != $user['answer']) {
                    $this->db->updateAnswer($securedPost['answer'], $user);
                }
                if (!empty($securedPost['new-password']) && !empty($securedPost['new-password-confirm'])) {
                    if (
                        $securedPost['new-password'] == $securedPost['new-password-confirm']
                        && !password_verify($securedPost['new-password'], $user['password'])
                    ) {
                        $this->db->updatePassword(
                            password_hash($securedPost['new-password'], PASSWORD_DEFAULT),
                            $user
                        );
                    }
                }
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
                } else if (isset($securedPost['answer'])) {
                    $this->addFlash("La réponse n'est pas la bonne !");
                }
                if (isset($securedPost['new-password']) && isset($securedPost['new-password-confirm'])) {
                    if ($securedPost['new-password'] == $securedPost['new-password-confirm']) {
                        $this->db->updatePassword(
                            password_hash($securedPost['new-password'], PASSWORD_DEFAULT),
                            $user
                        );
                        $this->addFlash("Nouveau mot de passe enregistré !");
                        App::redirect('/login');
                    } else {
                        $this->addFlash("Les mots de passe doivent être identique !");
                        App::redirect('/lost-password');
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
