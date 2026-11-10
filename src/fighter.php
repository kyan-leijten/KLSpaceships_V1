<?php
include_once 'Spaceship.php';

// Fighter: weinig HP, veel schade, en 15% kans op dubbele schade.
class Fighter extends Spaceship
{
    public int $critChance = 15;   // Kans op dubbele schade (procent)

    public function __construct()
    {
        parent::__construct(70, 30);   // 70 HP, 30 schade
    }

    // Eigen versie van shoot(): soms dubbele schade
    public function shoot(): int
    {
        if (rand(1, 100) <= $this->critChance) {
            return $this->damage * 2;
        }
        return $this->damage;
    }
}
