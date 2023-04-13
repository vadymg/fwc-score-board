<?php

namespace FWC\ScoreBoard\Services;

use FWC\ScoreBoard\DTO\Game;
use FWC\ScoreBoard\DTO\Team;

class GameService
{

    private TeamService $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function startGame(string $home, string $away): ?Game
    {
        $homeTeam = $this->teamService->getTeam($home);
        $awayTeam = $this->teamService->getTeam($away);
        // var_dump($home, $homeTeam, $awayTeam);
        if (!$homeTeam || !$awayTeam) {
            throw new \Exception('Invalid teams');
            return null;
        }

        return new Game($homeTeam, $awayTeam);
    }
}
