<?php
include_once 'Spaceship.php';

// Repairer: normaal schip, maar repareert dubbel.
class Repairer extends Spaceship
{
    public function __construct()
    {
        parent::__construct(100, 20);   // 100 HP, 20 schade
    }

    // Eigen versie van repair(): de gewone repair() twee keer
    public function repair(): void
    {
        parent::repair();
        parent::repair();
    }
}
