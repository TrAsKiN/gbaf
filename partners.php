<?php
require __DIR__ . '/includes/init.php';

$title = 'Groupement Banque-Assurance Français';

$partner = getPartner(htmlspecialchars($_GET["id"]));

if (!$partner) {
    notFound();
}

$grades = getGrades($partner);
$comments = getComments($partner);
$user = getUserByUsername($_SESSION['username']);

$gradesUp = [];
$gradesDown = [];
$classGradeUp = '';
$classGradeDown = '';
foreach ($grades as $grade) {
    if ($grade['grade']) {
        $gradesUp[] = $grade;
        if ($grade['id_user'] == $user['id']) {
            $classGradeUp = ' class="grade-selected"';
        }
    } else {
        $gradesDown[] = $grade;
        if ($grade['id_user'] == $user['id']) {
            $classGradeDown = ' class="grade-selected"';
        }
    }
}

$securedGet = array_map('htmlspecialchars', $_GET);
if (isset($securedGet['grade'])) {
    if (getGradeByUser($partner, $user)) {
        updateGrade($securedGet['grade'], $user);
    } else {
        addGrade($securedGet['grade'], $partner, $user);
    }
    redirect('/partners.php?id=' . $partner['id']);
}

$securedPost = array_map('htmlspecialchars', $_POST);
if (isset($securedPost['text-comment']) && !empty(trim($securedPost['text-comment']))) {
    if (!getCommentByUser($partner, $user)) {
        addComment($securedPost['text-comment'], $partner, $user);
        addFlash("Commentaire enregistré !");
        redirect('/partners.php?id=' . $partner['id']);
    } else {
        addFlash("Un commentaire est déjà enregistré !");
    }
}

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
    <section id="comments">
        <div id="actions">
            <h3><?= count($comments) ?> commentaire(s)</h3>
            <div id="grades">
                <a href="#nogo" id="toggle-new-comment">Nouveau commentaire</a>
                <div>
                    <a href="/partners.php?id=<?= $partner['id'] ?>&grade=1"<?= $classGradeUp ?>>
                        <?= count($gradesUp) ?> 👍
                    </a>
                    <a href="/partners.php?id=<?= $partner['id'] ?>&grade=0"<?= $classGradeDown ?>>
                        <?= count($gradesDown) ?> 👎
                    </a>
                </div>
            </div>
        </div>
        <form action="/partners.php?id=<?= $partner['id'] ?>" method="post" id="form-comment">
            <label>
                <textarea name="text-comment"></textarea>
            </label>
            <input type="submit" value="Envoyer">
        </form>

        <?php
        foreach ($comments as $comment) {
            $date = date_create($comment['date_add']);
            ?>

        <article class="comment">
            <h4 class="firstname"><?= $comment['firstname'] ?></h4>
            <p class="date"><?= $date->format('d/m/Y') ?></p>
            <p><?= nl2br($comment['comment']) ?></p>
        </article>

            <?php
        }
        ?>

    </section>
</main>

<?php
require __DIR__ . '/includes/_footer.php';
?>
