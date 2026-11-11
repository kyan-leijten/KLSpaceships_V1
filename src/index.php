<?php
// Eerst de classes laden, dan pas de sessie starten
include_once 'Battle.php';
include_once 'Scoreboard.php';
session_start();

// Pagina gewoon geopend of herladen (geen knop ingedrukt)? Dan reset het spel.
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    unset($_SESSION['battle']);
}

$scoreboard = new Scoreboard();

// Opnieuw beginnen: gevecht weggooien, terug naar het keuzescherm
if (isset($_POST['restart'])) {
    unset($_SESSION['battle']);
}

// Startschip gekozen: nieuw gevecht maken met dat schip vooraan
if (isset($_POST['start'])) {
    $_SESSION['battle'] = new Battle($_POST['start']);
}

// Het gevecht van nu, of null als je nog moet kiezen
$battle = $_SESSION['battle'] ?? null;

// Op een actieknop geklikt (alleen als het spel nog bezig is)
if ($battle && isset($_POST['action']) && !$battle->isGameOver()) {
    $battle->playTurn($_POST['action']);
}

// Naam ingevuld na game over: score opslaan, terug naar keuzescherm
if ($battle && isset($_POST['name']) && $battle->isGameOver()) {
    $scoreboard->saveScore(htmlspecialchars($_POST['name']), $battle->score);
    unset($_SESSION['battle']);
    $battle = null;
}

// Voor het keuzescherm: van elk type één schip maken om de waardes te laten zien
$options = [new Fighter(), new Tank(), new Repairer()];
$icons = ['Fighter' => '🚀', 'Tank' => '🛡️', 'Repairer' => '🔧'];
$specials = [
    'Fighter'  => '15% kans op dubbele schade',
    'Tank'     => 'Schild houdt 5 schade tegen',
    'Repairer' => 'Repareert dubbel',
];
?>
<!doctype html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ship Battle</title>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
</head>

<body>
    <main>
        <header>
            <h1>Ship Battle</h1>
            <?php if ($battle): ?>
                <p>Score: <strong><?= $battle->score ?></strong></p>
            <?php endif; ?>
        </header>

        <?php if (!$battle): ?>

            <!-- Keuzescherm: met welk schip begin je? -->
            <section class="choose">
                <h2>Kies je startschip</h2>
                <form method="post" class="choose-grid">
                    <?php foreach ($options as $ship): ?>
                        <button name="start" value="<?= $ship->getType() ?>" class="choose-card">
                            <span class="choose-icon"><?= $icons[$ship->getType()] ?></span>
                            <strong><?= $ship->getType() ?></strong>
                            <span><?= $ship->hitPoints ?> HP, <?= $ship->damage ?> schade</span>
                            <em><?= $specials[$ship->getType()] ?></em>
                        </button>
                    <?php endforeach; ?>
                </form>
                <p class="choose-note">De andere twee schepen zitten als reserve in je vloot.</p>
            </section>

        <?php elseif (!$battle->isGameOver()): ?>

            <?php
            $player = $battle->fleet->getActiveShip();
            $enemy = $battle->enemy;
            ?>

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

        <?php if ($battle): ?>
            <!-- Wat er gebeurde -->
            <section class="log">
                <?php foreach ($battle->log as $line): ?>
                    <p><?= $line ?></p>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>

        <div class="bottom">
            <?php if ($battle): ?>
                <section class="box">
                    <h3>Jouw vloot</h3>
                    <?= $battle->fleet->show() ?>
                    <form method="post">
                        <button name="restart" value="1" class="small">Opnieuw beginnen</button>
                    </form>
                </section>
            <?php endif; ?>

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