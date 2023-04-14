<?php

namespace FWC\ScoreBoard;

class ScoreBoard
{
    private const STORAGE_KEY = 'scoreboard';
    private array $scoreBoard = [];
    private IStorage $storage;

    public function __construct(IStorage $storage)
    {
        $this->storage = $storage;
        $this->scoreBoard = $storage->get(self::STORAGE_KEY);
    }

    public function startGame(string $homeTeam, string $awayTeam): void
    {
        $this->validateTeams($homeTeam, $awayTeam);
        $gameKey = $this->gameKey($homeTeam, $awayTeam);
        if ($this->hasGame($gameKey)) {
            throw new \Exception('Game already started');
        }
        $this->scoreBoard = array_merge($this->scoreBoard, $this->makeGame($homeTeam, $awayTeam));
        $this->saveScoreBoard();
    }

    public function finishGame(string $homeTeam, string $awayTeam): void
    {
        $this->validateTeams($homeTeam, $awayTeam);
        $gameKey = $this->gameKey($homeTeam, $awayTeam);
        if (!$this->hasGame($gameKey)) {
            throw new \Exception('Game has not started');
        }
        unset($this->scoreBoard[$gameKey]);
        $this->saveScoreBoard();
    }

    public function updateScore(string $homeTeam, string $awayTeam, int $homeScore, int $awayScore): void
    {
        $this->validateTeams($homeTeam, $awayTeam);
        $gameKey = $this->gameKey($homeTeam, $awayTeam);
        if (!$this->hasGame($gameKey)) {
            throw new \Exception('Game has not started');
        }
        $game = $this->getGame($gameKey);
        $game['homeScore'] = $homeScore;
        $game['awayScore'] = $awayScore;
        $game['totalScore'] = $homeScore + $awayScore;
        $this->scoreBoard[$gameKey] = $game;
        $this->saveScoreBoard();
    }

    public function getGames(): array
    {
        $games = array_values($this->scoreBoard);

        usort($games, function ($a, $b) {
            if ($a['totalScore'] === $b['totalScore']) {
                return $b['started'] <=> $a['started'];
            }
            return $b['totalScore'] <=> $a['totalScore'];
        });

        return $games;
    }

    public function getScoreBoard(): array
    {
        return $this->scoreBoard;
    }

    private function hasGame(string $gameKey): bool
    {
        return isset($this->scoreBoard[$gameKey]);
    }

    private function validateTeams(string $homeTeam, string $awayTeam): void
    {
        if ($homeTeam === $awayTeam) {
            throw new \Exception('Home team and away team cannot be the same');
        }
        if (!Team::inTeamList($homeTeam)) {
            throw new \Exception('Home team is not in the list of teams');
        }
        if (!Team::inTeamList($awayTeam)) {
            throw new \Exception('Away team is not in the list of teams');
        }
    }

    private function getGame(string $gameKey): array
    {
        return $this->scoreBoard[$gameKey] ?? [];
    }

    private function makeGame(string $homeTeam, string $awayTeam): array
    {
        return [
            $this->gameKey($homeTeam, $awayTeam) => [
                'homeTeam' => $homeTeam,
                'awayTeam' => $awayTeam,
                'homeScore' => 0,
                'awayScore' => 0,
                'totalScore' => 0,
                'started' => time(),
            ]
        ];
    }

    private function gameKey(string $homeTeam, string $awayTeam): string
    {
        return $homeTeam . '_' . $awayTeam;
    }

    private function saveScoreBoard(): void
    {
        $this->storage->save(self::STORAGE_KEY, $this->scoreBoard);
    }
}
