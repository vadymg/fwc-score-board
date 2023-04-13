<?php

namespace FWC\ScoreBoard\DTO;

class Game implements \JsonSerializable
{
    public string $id;
    public Team $homeTeam;
    public Team $awayTeam;
    public int $homeScore;
    public int $awayScore;
    public bool $finished = false;
    public \DateTimeImmutable $started;
    public \DateTimeImmutable $updated;

    public function __construct(Team $homeTeam, Team $awayTeam)
    {
        $this->id = uniqid();
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->homeScore = 0;
        $this->awayScore = 0;
        $this->started = new \DateTimeImmutable('now');
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'homeTeam' => $this->homeTeam,
            'awayTeam' => $this->awayTeam,
            'homeScore' => $this->homeScore,
            'awayScore' => $this->awayScore,
            'finished' => $this->finished,
            'started' => $this->started->format('Y-m-d H:i:s'),
            'updated' => $this->updated->format('Y-m-d H:i:s'),
        ];
    }
}
