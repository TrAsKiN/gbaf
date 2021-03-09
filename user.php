<?php
require __DIR__ . '/includes/init.php';

$title = 'Groupement Banque-Assurance Français';

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    unset($_SESSION['isConnected']);
    unset($_SESSION['username']);
    redirect('/user.php?action=login');
}

$action = null;
$user = null;

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'login':
            $action = 'Connexion';
            if (!empty($_POST)) {
                $securedPost = array_map('htmlspecialchars', $_POST);
                if ($user = getUserByUsername($securedPost['username'])) {
                    if (password_verify($securedPost['password'], $user['password'])) {
                        $_SESSION['username'] = $securedPost['username'];
                        $_SESSION['isConnected'] = true;
                        addFlash('Vous êtes connecté !');
                        redirect('/');
                    }
                }
                addFlash("Il y a une erreur avec votre identifiant ou votre mot de passe !");
            }
            break;
        case 'signup':
            $action = 'Inscription';
            if (!empty($_POST)) {
                $securedPost = array_map('htmlspecialchars', $_POST);
                if ($securedPost['password'] == $securedPost['password-confirm']) {
                    if (!getUserByUsername($securedPost['username'])) {
                        addUser([
                            'lastname' => $securedPost['lastname'],
                            'firstname' => $securedPost['firstname'],
                            'username' => $securedPost['username'],
                            'password' => password_hash($securedPost['password'], PASSWORD_DEFAULT),
                            'question' => $securedPost['question'],
                            'answer' => $securedPost['answer'],
                        ]);
                        $_SESSION['username'] = $securedPost['username'];
                        $_SESSION['isConnected'] = true;
                        addFlash("Inscription effectué !");
                        redirect('/user.php');
                    } else {
                        addFlash("L'identifiant existe déjà !");
                    }
                } else {
                    addFlash("Les mots de passe doivent être identique !");
                }
            }
            break;
        case 'lost-password':
            $action = 'Mot de passe perdu';
            if (!empty($_POST)) {
                $securedPost = array_map('htmlspecialchars', $_POST);
                $user = getUserByUsername($securedPost['username']);
                if ($user) {
                    if (isset($securedPost['answer']) && $user['answer'] != $securedPost['answer']) {
                        addFlash("La réponse n'est pas la bonne !");
                    }
                    if (isset($securedPost['new-password']) && isset($securedPost['new-password-confirm'])) {
                        if ($securedPost['new-password'] == $securedPost['new-password-confirm']) {
                            updatePassword(
                                password_hash($securedPost['new-password'], PASSWORD_DEFAULT),
                                $user
                            );
                            addFlash("Nouveau mot de passe enregistré !");
                            redirect('/user.php?action=login');
                        } else {
                            addFlash("Les mots de passe doivent être identiques !");
                            redirect('/user.php?action=lost-password');
                        }
                    }
                } else {
                    addFlash("Cet identifiant n'existe pas !");
                    redirect('/user.php?action=lost-password');
                }
            }
            break;
    }
} else {
    $action = 'Mon profil';
    $user = getUserByUsername($_SESSION['username']);
    if (!empty($_POST)) {
        $securedPost = array_map('htmlspecialchars', $_POST);
        if (password_verify($securedPost['password'], $user['password'])) {
            if (!empty($securedPost['lastname']) && $securedPost['lastname'] != $user['lastname']) {
                updateLastname($securedPost['lastname'], $user);
                addFlash("Nom modifié !");
            }
            if (!empty($securedPost['firstname']) && $securedPost['firstname'] != $user['firstname']) {
                updateFirstname($securedPost['firstname'], $user);
                addFlash("Prénom modifié !");
            }
            if (!empty($securedPost['question']) && $securedPost['question'] != $user['question']) {
                if (updateQuestion($securedPost['question'], $user)) {
                    addFlash("Question modifiée !");
                }
            }
            if (!empty($securedPost['answer']) && $securedPost['answer'] != $user['answer']) {
                if (updateAnswer($securedPost['answer'], $user)) {
                    addFlash("Réponse modifiée !");
                }
            }
            if (!empty($securedPost['new-password']) && !empty($securedPost['new-password-confirm'])) {
                if ($securedPost['new-password'] == $securedPost['new-password-confirm']
                    && !password_verify($securedPost['new-password'], $user['password'])
                    && updatePassword(
                        password_hash($securedPost['new-password'], PASSWORD_DEFAULT),
                        $user
                    )
                ) {
                    addFlash("Mot de passe modifié !");
                }
            }
            redirect('/user.php');
        } else {
            addFlash("Le mot de passe est incorrect !");
        }
    }
}

require __DIR__ . '/includes/_header.php';
?>

<main>
    <div id="user">
        <h1><?= $action ?></h1>

        <?php
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'login':
                    ?>

        <form action="/user.php?action=login" method="post">
            <div>
                <label for="username">Identifiant</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div>
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>
                <p><a href="/user.php?action=lost-password">Mot de passe oublié ?</a></p>
            </div>
            <div>
                <input type="submit" value="Valider">
            </div>
        </form>
        <p>Vous n'avez pas de compte ? <a href="/user.php?action=signup">S'inscrire</a></p>

                    <?php
                    break;
                case 'signup':
                    ?>

        <form action="/user.php?action=signup" method="post">
            <fieldset class="inline">
                <div>
                    <label for="lastname">Nom</label>
                    <input type="text" name="lastname" id="lastname" required>
                </div>
                <div>
                    <label for="firstname">Prénom</label>
                    <input type="text" name="firstname" id="firstname" required>
                </div>
            </fieldset>
            <fieldset>
                <div>
                    <label for="username">Identifiant</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div>
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div>
                    <label for="password-confirm">Confirmer le mot de passe</label>
                    <input type="password" name="password-confirm" id="password-confirm" required>
                </div>
            </fieldset>
            <fieldset>
                <div>
                    <label for="question">Question secrète</label>
                    <input type="text" name="question" id="question" required>
                </div>
                <div>
                    <label for="answer">Réponse</label>
                    <input type="text" name="answer" id="answer" required>
                </div>
            </fieldset>
            <div>
                <input type="submit" value="S'inscrire">
            </div>
        </form>

                    <?php
                    break;
                case 'lost-password':
                    if (!empty($_POST)) {
                        $securedPost = array_map('htmlspecialchars', $_POST);
                        $user = getUserByUsername($securedPost['username']);
                        if ($user) {
                            if (isset($securedPost['answer']) && $user['answer'] == $securedPost['answer']) {
                                ?>

        <form action="/user.php?action=lost-password" method="post">
            <input type="hidden" name="username" value="<?= $user['username'] ?>">
            <div>
                <label for="new-password">Nouveau mot de passe</label>
                <input type="password" name="new-password" id="new-password" required>
            </div>
            <div>
                <label for="new-password-confirm">Confirmer le nouveau mot de passe</label>
                <input type="password" name="new-password-confirm" id="new-password-confirm" required>
            </div>
            <div>
                <input type="submit" value="Enregistrer">
            </div>
        </form>

                                <?php
                            } else {
                                ?>

        <form action="/user.php?action=lost-password" method="post">
            <input type="hidden" name="username" value="<?= $user['username'] ?>">
            <p><?= $user['question'] ?></p>
            <div>
                <label for="answer">Réponse</label>
                <input type="text" name="answer" id="answer" required>
            </div>
            <div>
                <input type="submit" value="Valider">
            </div>
        </form>

                                <?php
                            }
                        }
                    } else {
                        ?>

    <form action="/user.php?action=lost-password" method="post">
        <div>
            <label for="username">Identifiant</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <input type="submit" value="Valider">
        </div>
    </form>

                        <?php
                    }
                    break;
            }
        } else {
            ?>

        <form action="/user.php" method="post">
            <fieldset class="inline">
                <div>
                    <label for="lastname">Nom</label>
                    <input type="text" name="lastname" id="lastname" value="<?= $user['lastname'] ?>">
                </div>
                <div>
                    <label for="firstname">Prénom</label>
                    <input type="text" name="firstname" id="firstname" value="<?= $user['firstname'] ?>">
                </div>
            </fieldset>
            <fieldset>
                <div>
                    <label for="new-password">Nouveau mot de passe</label>
                    <input type="password" name="new-password" id="new-password">
                </div>
                <div>
                    <label for="new-password-confirm">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="new-password-confirm" id="new-password-confirm">
                </div>
            </fieldset>
            <fieldset>
                <div>
                    <label for="question">Question secrète</label>
                    <input type="text" name="question" id="question" value="<?= $user['question'] ?>">
                </div>
                <div>
                    <label for="answer">Réponse</label>
                    <input type="text" name="answer" id="answer" value="<?= $user['answer'] ?>">
                </div>
            </fieldset>
            <div>
                <label for="password"><strong>Mot de passe</strong> <small>(<em>Obligatoire</em>)</small></label>
                <input type="password" name="password" id="password" required>
            </div>
            <div>
                <input type="submit" value="Enregistrer">
            </div>
        </form>

            <?php
        }
        ?>

    </div>
</main>

<?php
require __DIR__ . '/includes/_footer.php';
?>
