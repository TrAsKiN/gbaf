<?php
$user = null;

if (isset($_SESSION['isConnected']) && $_SESSION['isConnected']) {
    global $user;
    $user = getUserByUsername($_SESSION['username']);
    if (!$user) {
        addFlash("Erreur avec les informations utilisateur !");
        redirect('/user.php?action=logout');
    }
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="css/bootstrap-reboot.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <div>
            <a href="/"><img src="images/logo.png" alt="GBAF"></a>
        </div>

        <?php
        if ($user) {
            ?>

        <div class="profile">

            <div>
                <?= $user['lastname'] . ' ' . $user['firstname']; ?>
            </div>
            <div>
                <a href="/user.php">Paramètres du compte</a>
            </div>
            <div>
                <a href="/user.php?action=logout">Se déconnecter</a>
            </div>

        </div>

            <?php
        }
        ?>

    </nav>

    <?php
    if (isset($_SESSION['flashMessages']) && !empty($_SESSION['flashMessages'])) {
        while ($message = array_shift($_SESSION['flashMessages'])) {
            ?>

            <p class="alert"><?= $message ?></p>

            <?php
        }
    }
    ?>
