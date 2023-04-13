<?php

namespace FWC\ScoreBoard\Tests;

use FWC\ScoreBoard\DTO\Game;
use FWC\ScoreBoard\Services\GameService;
use FWC\ScoreBoard\Services\TeamService;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FWC\ScoreBoard\Services\GameService
 * @uses \FWC\ScoreBoard\Services\TeamService
 */
class GameServiceTest extends TestCase
{
    /**
     * @var TeamService | \PHPUnit\Framework\MockObject\MockObject
     */
    private TeamService $teamService;

    public function setUp(): void
    {
        $this->teamService = new TeamService(['Team 1', 'Team 2']);
    }

    public function testAddGame(): void
    {
        $gameService  = new GameService($this->teamService);
        $game = $gameService->startGame('Team 1', 'Team 2');
        $this->assertInstanceOf(Game::class,  $game);
    }

    public function testAddGameWithInvalidTeams(): void
    {
        $gameService  = new GameService($this->teamService);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid teams');
        $game = $gameService->startGame('Team 1', 'Team 3');
        $this->assertNull($game);
    }
}
