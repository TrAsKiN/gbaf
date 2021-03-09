<?php
require __DIR__ . '/includes/init.php';

$title = 'Groupement Banque-Assurance Français';

$partners = getPartners();

require __DIR__ . '/includes/_header.php';
?>

<main>
    <h1>Groupement Banque-Assurance Français</h1>
    <p>
        Le Groupement Banque Assurance Français (GBAF) est une fédération représentant les 6 grands groupes
        français :
    </p>
    <ul>
        <li>BNP Paribas ;</li>
        <li>BPCE ;</li>
        <li>Crédit Agricole ;</li>
        <li>Crédit Mutuel-CIC ;</li>
        <li>Société Générale ;</li>
        <li>La Banque Postale.</li>
    </ul>
    <p>
        Le GBAF est le représentant de la profession bancaire et des assureurs sur tous les axes de la réglementation
        financière française. Sa mission est de promouvoir l'activité bancaire à l’échelle nationale. C’est aussi un
        interlocuteur privilégié des pouvoirs publics.
    </p>
    <figure>
        <img src="images/illustration_gbaf.jpg" alt="Logo du GBAF">
        <figcaption>Logo du Groupement Banque-Assurance Français</figcaption>
    </figure>
    <section class="partners">
        <h2>Acteurs et partenaires</h2>
        <p>Voici la liste des partenaires répertoriés.</p>

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
                <p><?= nl2br(strtok($partner['description'], PHP_EOL)) ?></p>

                <?php
                if ($partner['website']) {
                    ?>

                <p><a href="<?= $partner['website'] ?>">Site internet</a></p>

                    <?php
                }
                ?>

            </div>
            <p>
                <a href="/partners.php?id=<?= $partner['id'] ?>">Afficher la suite</a>
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
