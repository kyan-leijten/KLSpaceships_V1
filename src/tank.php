<?php
include_once 'Spaceship.php';

// Tank: veel HP, weinig schade, en een schild dat 5 schade tegenhoudt.
class Tank extends Spaceship
{
    public int $shield = 5;   // Zoveel schade houdt het schild tegen

    public function __construct()
    {
        parent::__construct(150, 15);   // 150 HP, 15 schade
    }

    // Eigen versie van hit(): eerst het schild eraf, dan de gewone hit()
    public function hit(int $damage): void
    {
        parent::hit(max(0, $damage - $this->shield));
    }
}
