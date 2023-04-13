<?php

namespace FWC\ScoreBoard\Tests;

use FWC\ScoreBoard\DTO\Team;
use FWC\ScoreBoard\Services\TeamService;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FWC\ScoreBoard\Services\TeamService
 */
final class TeamServiceTest extends TestCase
{
    public function testAddTeam(): void
    {
        $teamService = new TeamService();
        $teamService->addTeam('Team 1');
        $teamService->addTeam('Team 2');
        $teamService->addTeam('Team 3');
        $this->assertCount(3, $teamService->getTeams());
    }

    public function testGetTeam(): void
    {
        $teamService = new TeamService();
        $teamService->addTeam('Team 1');
        $teamService->addTeam('Team 2');
        $teamService->addTeam('Team 3');
        $this->assertInstanceOf(Team::class, $teamService->getTeam('Team 1'));
    }

    public function testGetTeams(): void
    {
        $teamService = new TeamService();
        $teamService->addTeam('Team 1');
        $teamService->addTeam('Team 2');
        $teamService->addTeam('Team 3');
        $this->assertCount(3, $teamService->getTeams());
    }
}
