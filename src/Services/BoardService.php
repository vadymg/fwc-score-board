<?php

namespace FWC\ScoreBoard\Services;

use FWC\ScoreBoard\DTO\Board;
use FWC\ScoreBoard\DTO\Game;
use FWC\ScoreBoard\Storage\IStorage;

class BoardService
{
    private Board $board;

    private GameService $gameService;
    private IStorage $storage;

    public function __construct(GameService $gameService, IStorage $storage)
    {
        $this->storage = $storage;
        $this->gameService = $gameService;
        $this->initBoard();
    }

    public function startGame(string $home, string $away): void
    {
        try {
            $this->board->games[] = $this->gameService->startGame($home, $away);
            $this->saveBoard();
        } catch (\Exception $e) {
            throw new \Exception('Invalid teams');
        }
    }

    public function updateScoreById(string $id, int $homeScore, int $awayScore): void
    {
        $game = $this->getGameById($id);
        if (!$game) {
            throw new \Exception('Invalid game');
        }
        $game->homeScore = $homeScore < 0 ? 0 : $homeScore;
        $game->awayScore = $awayScore < 0 ? 0 : $awayScore;
        $game->updated = new \DateTimeImmutable();
        $this->saveBoard();
    }

    public function finishGame(string $id): void
    {
        $game = $this->getGameById($id);
        if (!$game) {
            throw new \Exception('Invalid game');
        }

        $game->finished = true;
        $game->updated = new \DateTimeImmutable();
        $this->saveBoard();
    }

    public function getFinishedGames(): array
    {
        return array_filter($this->board->games, function (Game $game) {
            return $game->finished;
        });
    }

    public function getGameById(string $id): ?Game
    {
        $games = array_filter($this->board->games, function (Game $game) use ($id) {
            return $game->id === $id;
        });

        return array_shift($games);
    }

    public function getGames(): array
    {
        return array_filter($this->board->games, function (Game $game) {
            return !$game->finished;
        });
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    private function initBoard(): void
    {
        if (!$data = $this->storage->load()) {
            $this->board = new Board('World Cup');
            return;
        }

        $this->board = unserialize($data);
    }

    private function saveBoard(): void
    {
        $this->storage->save(serialize($this->board));
    }
}
