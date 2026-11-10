<?php
// Eerst de classes laden, dan pas de sessie starten
include_once 'Battle.php';
include_once 'Scoreboard.php';
session_start();

// Geen gevecht bezig of op "opnieuw" geklikt? Nieuw gevecht maken.
if (!isset($_SESSION['battle']) || isset($_POST['restart'])) {
    $_SESSION['battle'] = new Battle();
}
$battle = $_SESSION['battle'];
$scoreboard = new Scoreboard();

// Op een actieknop geklikt
if (isset($_POST['action'])) {
    $battle->playTurn($_POST['action']);
}

// Naam ingevuld na game over: score opslaan en opnieuw beginnen
if (isset($_POST['name']) && $battle->isGameOver()) {
    $scoreboard->saveScore(htmlspecialchars($_POST['name']), $battle->score);
    $_SESSION['battle'] = new Battle();
    $battle = $_SESSION['battle'];
}

$player = $battle->fleet->getActiveShip();
$enemy = $battle->enemy;
?>
<!doctype html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ship Battle</title>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main>
        <header>
            <h1>Ship Battle</h1>
            <p>Score: <strong><?= $battle->score ?></strong></p>
        </header>

        <?php if (!$battle->isGameOver()): ?>

            <!-- Jouw schip tegen de vijand -->
            <section class="arena">
                <div class="ship player">
                    <div class="icon">🚀</div>
                    <h2><?= $player->getType() ?></h2>
                    <progress value="<?= $player->hitPoints ?>" max="<?= $player->maxHitPoints ?>"></progress>
                    <p><?= $player->hitPoints ?> / <?= $player->maxHitPoints ?> HP</p>
                </div>

                <div class="vs">vs</div>

                <div class="ship enemy">
                    <div class="icon">🛸</div>
                    <h2><?= $enemy->getType() ?></h2>
                    <progress value="<?= $enemy->hitPoints ?>" max="<?= $enemy->maxHitPoints ?>"></progress>
                    <p><?= $enemy->hitPoints ?> / <?= $enemy->maxHitPoints ?> HP</p>
                </div>
            </section>

            <!-- De drie keuzes -->
            <form method="post" class="actions">
                <button name="action" value="aanvallen">⚔️ Aanvallen</button>
                <button name="action" value="ontwijken">💨 Ontwijken</button>
                <button name="action" value="repareren">🔧 Repareren</button>
            </form>

        <?php else: ?>

            <!-- Game over: naam invullen -->
            <section class="game-over">
                <h2>Game over</h2>
                <p>Je hebt <?= $battle->score ?> vijanden verslagen.</p>
                <form method="post">
                    <input name="name" placeholder="Jouw naam" maxlength="20" required>
                    <button>Opslaan</button>
                </form>
            </section>

        <?php endif; ?>

        <!-- Wat er gebeurde -->
        <section class="log">
            <?php foreach ($battle->log as $line): ?>
                <p><?= $line ?></p>
            <?php endforeach; ?>
        </section>

        <div class="bottom">
            <section class="box">
                <h3>Jouw vloot</h3>
                <?= $battle->fleet->show() ?>
                <form method="post">
                    <button name="restart" value="1" class="small">Opnieuw beginnen</button>
                </form>
            </section>

            <section class="box">
                <h3>Top 10</h3>
                <ol>
                    <?php foreach ($scoreboard->getTop10() as $row): ?>
                        <li><?= $row['name'] ?> <span><?= $row['score'] ?></span></li>
                    <?php endforeach; ?>
                </ol>
            </section>
        </div>
    </main>
    <script src="script.js"></script>
</body>

</html>