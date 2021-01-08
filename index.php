<?php
require __DIR__ . '/includes/init.php';

$title = 'Groupement Banque-Assurance Français';

$partners = getPartners();

require __DIR__ . '/includes/_header.php';
?>

<main>
    <h1>Groupement Banque-Assurance Français</h1>
    <p>Texte de présentation du GBAF et du site.</p>
    <figure>
        <img src="images/illustration_gbaf.jpg" alt="Logo du GBAF">
        <figcaption>Logo du Groupement Banque-Assurance Français</figcaption>
    </figure>
    <section class="partners">
        <h2>Acteurs et partenaires</h2>
        <p>Texte acteurs et partenaires</p>

        <?php
        if ($partners) {
            foreach ($partners as $partner) {
        ?>

        <article class="partner">
            <figure>
                <img src="images/partners/<?= $partner['logo'] ?>" alt="Logo de <?= $partner['name'] ?>">
            </figure>
            <div>
                <h3><?= $partner['name'] ?></h3>
                <p><?= nl2br($partner['description']) ?></p>

                <?php
                if ($partner['website']) {
                ?>

                <p><a href="<?= $partner['website'] ?>">Site internet</a></p>

                <?php
                }
                ?>

            </div>
            <p>
                <a href="/partners.php?id=<?= $partner['id'] ?>">Lire la suite</a>
            </p>
        </article>

        <?php
            }
        } else {
        ?>

        <p>Aucun acteur/partenaire à afficher.</p>

        <?php
        }
        ?>

    </section>
</main>

<?php
require __DIR__ . '/includes/_footer.php';
?>
