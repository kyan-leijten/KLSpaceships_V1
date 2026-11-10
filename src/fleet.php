<?php
include_once 'Fighter.php';
include_once 'Tank.php';
include_once 'Repairer.php';

// Fleet: een lijst met schepen (zoals het Autopark uit de les).
// Het eerste schip in de lijst is het schip dat nu vecht.
class Fleet
{
    private array $ships = [];

    // Schip toevoegen aan de lijst
    public function addShip(Spaceship $ship): void
    {
        $this->ships[] = $ship;
    }

    // Schip uit de lijst halen
    public function removeShip(Spaceship $ship): void
    {
        $key = array_search($ship, $this->ships, true);
        unset($this->ships[$key]);
        $this->ships = array_values($this->ships);   // Nummering weer 0, 1, 2
    }

    // Het schip dat nu vecht (het eerste)
    public function getActiveShip(): ?Spaceship
    {
        return $this->ships[0] ?? null;
    }

    // Hoeveel schepen er nog over zijn
    public function countShips(): int
    {
        return count($this->ships);
    }

    // Alle schepen als HTML-lijstje
    public function show(): string
    {
        $html = '<ul>';
        foreach ($this->ships as $ship) {
            $html .= "<li>{$ship->getType()} ({$ship->hitPoints} HP)</li>";
        }
        return $html . '</ul>';
    }
}
