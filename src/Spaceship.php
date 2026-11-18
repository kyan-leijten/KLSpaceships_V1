<?php

namespace Spaceships;
// Spaceship is een abstracte parent-class: je maakt er nooit zelf een object van,
// alleen van de subclasses Fighter, Tank en Repairer.
abstract class Spaceship
{
    public int $hitPoints;     // Levenspunten nu
    public int $maxHitPoints;  // Maximum levenspunten
    public int $damage;        // Schade per schot
    public bool $isAlive;      // Leeft het schip nog?

    // Wordt uitgevoerd bij "new Fighter()" enz. via parent::__construct()
    public function __construct($hitPoints = 100, $damage = 20)
    {
        $this->hitPoints = $hitPoints;
        $this->maxHitPoints = $hitPoints;
        $this->damage = $damage;
        $this->isAlive = true;
    }

    // Schieten: geeft terug hoeveel schade het schot doet
    public function shoot(): int
    {
        return $this->damage;
    }

    // Geraakt worden: HP omlaag, bij 0 is het schip kapot
    public function hit(int $damage): void
    {
        $this->hitPoints -= $damage;

        if ($this->hitPoints <= 0) {
            $this->hitPoints = 0;
            $this->isAlive = false;
        }
    }

    // Repareren: 20 HP erbij, maar niet boven het maximum
    public function repair(): void
    {
        $this->hitPoints = min($this->hitPoints + 20, $this->maxHitPoints);
    }

    // Geeft de naam van de class terug, bijv. "Fighter"
    public function getType(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    // Abstracte functie: leeg vakje. Elk schip MOET zelf vertellen wat zijn speciale ding is.
    abstract public function getSpecial(): string;
}
