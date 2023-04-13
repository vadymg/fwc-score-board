<?php

namespace FWC\ScoreBoard\Services;

use FWC\ScoreBoard\DTO\Game;

class BoardResultService
{
    private BoardService $boardService;

    public function __construct(BoardService $board)
    {
        $this->boardService = $board;
    }

    public function getBoardResult(): array
    {
        return [
            'games' => $this->getGamesSummary(),
            'finishedGames' => $this->getFinishedGamesSummery(),
        ];
    }

    public function getGamesSummary(): array
    {
        $games = $this->boardService->getGames();
        usort($games, [$this, 'sortByStartedDate']);
        $summary = [];
        foreach ($games as $game) {
            $summary[] = $this->getGameSummary($game);
        }

        return $summary;
    }

    public function getFinishedGamesSummery(): array
    {
        $finishedGames = $this->boardService->getFinishedGames();
        usort($finishedGames, [$this, 'sortByStartedDate']);
        $summary = [];
        foreach ($finishedGames as $game) {
            $summary[] = $this->getGameSummary($game);
        }

        return $summary;
    }

    public function getGameSummary(Game $game): array
    {
        return [
            'id' => $game->id,
            'home' => $game->homeTeam->name,
            'away' => $game->awayTeam->name,
            'homeScore' => $game->homeScore,
            'awayScore' => $game->awayScore,
            'started' => $game->started->format('Y-m-d H:i:s'),
            'finished' => $game->finished ? $game->updated->format('Y-m-d H:i:s') : null,
            'winner' => $this->getWinner($game),
        ];
    }

    private function getWinner(Game $game): string
    {
        if ($game->homeScore > $game->awayScore) {
            return $game->homeTeam->name;
        }
        if ($game->homeScore < $game->awayScore) {
            return $game->awayTeam->name;
        }
        if ($game->finished === true && $game->homeScore === $game->awayScore) {
            return 'Draw';
        }

        return 'Not finished yet';
    }

    private function sortByStartedDate(Game $game1, Game $game2): int
    {
        return $game2->started <=> $game1->started;
    }
}
