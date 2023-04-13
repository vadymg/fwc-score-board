<?php

namespace FWC\ScoreBoard\Tests;

use FWC\ScoreBoard\Services\BoardService;
use FWC\ScoreBoard\Services\GameService;
use FWC\ScoreBoard\Services\TeamService;
use FWC\ScoreBoard\Storage\IStorage;

/**
 * @covers \FWC\ScoreBoard\Services\BoardService
 * @uses \FWC\ScoreBoard\Services\GameService
 * @uses \FWC\ScoreBoard\Services\TeamService
 */
final class BoardServiceTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \FWC\ScoreBoard\Services\BoardService
     */
    private $boardService;

    public function setUp(): void
    {
        $storage = $this->createMock(IStorage::class);
        $teamService = new TeamService(['Team 1', 'Team 2']);
        $gameService = new GameService($teamService);

        $this->boardService = new BoardService($gameService, $storage);

        $storage->method('save');
        $storage->method('load')->willReturn(serialize($this->boardService));
    }

    public function testStartGame(): void
    {
        $this->boardService->startGame('Team 1', 'Team 2');
        $this->assertCount(1, $this->boardService->getGames());
    }

    public function testStartGameWithInvalidTeams(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid teams');
        $this->boardService->startGame('Team 1', 'Team 3');
    }

    public function testUpdateScoreById(): void
    {
        $this->boardService->startGame('Team 1', 'Team 2');
        /** @var \FWC\ScoreBoard\DTO\Game $game */
        $game = $this->boardService->getGames()[0];
        $this->boardService->updateScoreById($game->id, 1, 1);

        $this->assertEquals(1, $game->homeScore);
    }

    public function testUpdateScoreByIdWithInvalidId(): void
    {
        $this->boardService->startGame('Team 1', 'Team 2');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid game');
        $this->boardService->updateScoreById('invalid id', 1, 1);
    }
}
