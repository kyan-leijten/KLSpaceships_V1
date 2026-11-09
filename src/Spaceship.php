<?php

/**
 * Class Spaceship
 * De blauwdruk voor een ruimteschip in Ship Battle.
 * Zowel de speler als de vijand zijn een object van deze class.
 */
class Spaceship
{
    // Eigenschappen (properties)
    public bool $isAlive;      // Leeft het schip nog?
    public int $ammo;          // Hoeveel munitie het schip nog heeft
    public int $hitPoints;     // Huidige levenspunten
    public int $maxHitPoints;  // Maximum HP, repareren gaat hier nooit boven

    /**
     * Constructor: wordt uitgevoerd bij "new Spaceship()".
     * Zonder parameters krijgt het schip de standaardwaarden.
     */
    public function __construct($ammo = 100, $hitPoints = 100)
    {
        $this->ammo = $ammo;
        $this->hitPoints = $hitPoints;
        $this->maxHitPoints = $hitPoints; // Startwaarde is meteen het maximum
        $this->isAlive = true;            // Een nieuw schip leeft altijd
    }

    /**
     * Schieten: kost munitie en geeft de schade terug.
     * Is de munitie op, dan doet het schot 0 schade.
     */
    public function shoot(): int
    {
        $ammoPerShot = 5;
        $damage = 20;

        if ($this->ammo - $ammoPerShot >= 0) {
            $this->ammo -= $ammoPerShot;
            return $damage;
        }

        return 0;
    }

    /**
     * Geraakt worden: haalt schade van de HP af.
     * Komt de HP op 0 of lager, dan is het schip kapot.
     */
    public function hit(int $damage): void
    {
        $this->hitPoints -= $damage;

        if ($this->hitPoints <= 0) {
            $this->hitPoints = 0;      // Nooit negatieve HP laten zien
            $this->isAlive = false;
        }
    }

    /**
     * Repareren: geeft HP terug, maar nooit meer dan het maximum.
     */
    public function repair(): void
    {
        $repairAmount = 15;
        $this->hitPoints += $repairAmount;

        if ($this->hitPoints > $this->maxHitPoints) {
            $this->hitPoints = $this->maxHitPoints;
        }
    }
}
