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
            <a href="./"><img src="images/logo.png" alt="GBAF"></a>
        </div>
        <div class="profile">

            <div>
                {LASTNAME} {FIRSTNAME}
            </div>
            <div>
                <a href="user.php">Modifer mon profil</a>
            </div>
            <div>
                <a href="user.php?action=logout">Déconnexion</a>
            </div>

        </div>
    </nav>

    <?php
    if (isset($_SESSION['flashMessages']) && !empty($_SESSION['flashMessages'])) {
        while($message = array_shift($_SESSION['flashMessages'])) {
            ?>

            <p class="alert"><?= $message ?></p>

            <?php
        }
    }
    ?>
