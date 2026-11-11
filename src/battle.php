<?php
include_once 'Fleet.php';

// Battle: jouw vloot tegen steeds een nieuwe vijand.
// Aanvallen wint van repareren, ontwijken wint van aanvallen,
// repareren wint van ontwijken.
class Battle
{
    public Fleet $fleet;       // Jouw schepen
    public Spaceship $enemy;   // De vijand
    public int $score = 0;     // Verslagen vijanden
    public array $log = [];    // Wat er gebeurde

    // Wordt uitgevoerd bij "new Battle('Tank')".
    // Het gekozen schip komt vooraan in de vloot, de andere twee erachter.
    public function __construct(string $startShip)
    {
        $ships = [
            'Fighter'  => new Fighter(),
            'Tank'     => new Tank(),
            'Repairer' => new Repairer(),
        ];

        // Onbekende keuze? Dan gewoon met de Fighter beginnen
        if (!isset($ships[$startShip])) {
            $startShip = 'Fighter';
        }

        $this->fleet = new Fleet();
        $this->fleet->addShip($ships[$startShip]);   // Gekozen schip eerst
        unset($ships[$startShip]);                   // Uit het lijstje halen

        foreach ($ships as $ship) {                  // De rest erachter
            $this->fleet->addShip($ship);
        }

        $this->newEnemy();
    }

    // Eén beurt spelen
    public function playTurn(string $choice): void
    {
        // Al game over (vloot leeg)? Dan valt er niks meer te spelen.
        if ($this->isGameOver()) {
            return;
        }

        $player = $this->fleet->getActiveShip();
        $enemyChoice = ['aanvallen', 'ontwijken', 'repareren'][rand(0, 2)];
        $this->log = ["Jij kiest $choice, de vijand kiest $enemyChoice."];

        // Aanvallen (mist als de ander ontwijkt)
        if ($choice == 'aanvallen' && $enemyChoice != 'ontwijken') {
            $this->enemy->hit($player->shoot());
            $this->log[] = "Je raakt de vijand!";
        }
        if ($enemyChoice == 'aanvallen' && $choice != 'ontwijken') {
            $player->hit($this->enemy->shoot());
            $this->log[] = "De vijand raakt jou!";
        }

        // Repareren (mislukt als de ander aanvalt)
        if ($choice == 'repareren' && $enemyChoice != 'aanvallen') {
            $player->repair();
            $this->log[] = "Je repareert je schip.";
        }
        if ($enemyChoice == 'repareren' && $choice != 'aanvallen') {
            $this->enemy->repair();
            $this->log[] = "De vijand repareert.";
        }

        // Vijand kapot? Punt erbij en een nieuwe vijand
        if (!$this->enemy->isAlive) {
            $this->score++;
            $this->newEnemy();
            $this->log[] = "Vijand vernietigd!";
        }

        // Jouw schip kapot? Uit de vloot, het volgende neemt het over
        if (!$player->isAlive) {
            $this->fleet->removeShip($player);
            $this->log[] = "Je {$player->getType()} is vernietigd!";
        }
    }

    // Nieuwe vijand van een willekeurig type
    private function newEnemy(): void
    {
        $types = [new Fighter(), new Tank(), new Repairer()];
        $this->enemy = $types[rand(0, 2)];
    }

    // Game over als je vloot leeg is
    public function isGameOver(): bool
    {
        return $this->fleet->countShips() == 0;
    }
}
