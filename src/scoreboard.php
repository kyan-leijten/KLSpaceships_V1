<?php
// Scoreboard: slaat scores op in een bestand (scores.json).
class Scoreboard
{
    private string $file = __DIR__ . '/scores.json';

    // Score toevoegen
    public function saveScore(string $name, int $score): void
    {
        $scores = $this->getAll();
        $scores[] = ['name' => $name, 'score' => $score];
        file_put_contents($this->file, json_encode($scores));
    }

    // Top 10, hoogste score eerst
    public function getTop10(): array
    {
        $scores = $this->getAll();
        usort($scores, fn($a, $b) => $b['score'] <=> $a['score']);
        return array_slice($scores, 0, 10);
    }

    // Alle scores uit het bestand halen
    private function getAll(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }
        return json_decode(file_get_contents($this->file), true) ?? [];
    }
}
