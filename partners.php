<?php
require __DIR__ . '/includes/init.php';

$title = 'Groupement Banque-Assurance Français';

$partner = getPartner(htmlspecialchars($_GET["id"]));

if (!$partner) {
    notFound();
}

$grades = getGrades(htmlspecialchars($_GET["id"]));
$comments = getComments(htmlspecialchars($_GET["id"]));

require __DIR__ . '/includes/_header.php';
?>

<main>
    <section>
        <figure>
            <img src="images/partners/<?= $partner['logo'] ?>" alt="Logo de <?= $partner['name'] ?>">
            <figcaption>Logo de <?= $partner['name'] ?></figcaption>
        </figure>
        <h2><?= $partner['name'] ?></h2>

        <?php
        if ($partner['website']) {
        ?>

        <p><a href="<?= $partner['website'] ?>">Site internet</a></p>

        <?php
        }
        ?>

        <p><?= nl2br($partner['description']) ?></p>
    </section>
    <aside>
        <h3><?= count($comments) ?> commentaire(s)</h3>
    </aside>
</main>

<?php
require __DIR__ . '/includes/_footer.php';
?>
