<?php

namespace FWC\ScoreBoard\Tests;

use FWC\ScoreBoard\ScoreBoard;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FWC\ScoreBoard\ScoreBoard
 */
class ScoreBoardTest extends TestCase
{
    private $fixture = [];

    protected function setUp(): void
    {
        $this->fixture = require __DIR__ . '/fixtures/scoreboard.php';
    }

    public function testStartGame()
    {
        /** @var \FWC\ScoreBoard\IStorage $storage */
        $storage = $this->getStartedGameStorageMock(true);

        $scoreBoard = new ScoreBoard($storage);
        $scoreBoard->startGame('Mexico', 'Canada');
        $startedGames = $scoreBoard->getGames();

        $this->assertCount(1, $startedGames);
        $this->assertEquals('Mexico', $startedGames[0]['homeTeam']);
    }

    public function testStartGameExceptionGameAlreadyStarted()
    {
        /** @var \FWC\ScoreBoard\IStorage $storage */
        $storage = $this->getStartedGameStorageMock();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Game already started');

        $scoreBoard = new ScoreBoard($storage);
        $scoreBoard->startGame('Mexico', 'Canada');
    }

    public function testStartGameExceptionHomeTeamIsNotInTheListOfTeams()
    {
        /** @var \FWC\ScoreBoard\IStorage $storage */
        $storage = $this->getStartedGameStorageMock();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Home team is not in the list of teams');

        $scoreBoard = new ScoreBoard($storage);
        $scoreBoard->startGame('Mexico1', 'Mexico');
    }

    public function testStartGameExceptionAwayTeamIsNotInTheListOfTeams()
    {
        /** @var \FWC\ScoreBoard\IStorage $storage */
        $storage = $this->getStartedGameStorageMock();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Away team is not in the list of teams');

        $scoreBoard = new ScoreBoard($storage);
        $scoreBoard->startGame('Mexico', 'Mexico1');
    }

    public function testStartGameExceptionTheSameTeams()
    {
        /** @var \FWC\ScoreBoard\IStorage $storage */
        $storage = $this->getStartedGameStorageMock();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Home team and away team cannot be the same');

        $scoreBoard = new ScoreBoard($storage);
        $scoreBoard->startGame('Mexico', 'Mexico');
    }

    public function testFinishGame()
    {
        /** @var \FWC\ScoreBoard\IStorage = $storage */
        $storage = $this->getFinishGameStorageMock();

        $scoreBoard = new ScoreBoard($storage);
        $scoreBoard->finishGame('Germany', 'France');
        $startedGames = $scoreBoard->getGames();

        $this->assertCount(0, $startedGames);
    }

    public function testUpdateScore()
    {
        /** @var \FWC\ScoreBoard\IStorage = $storage */
        $storage = $this->getUpdateScoreStorageMock();

        $scoreBoard = new ScoreBoard($storage);
        $scoreBoard->updateScore('Uruguay', 'Italy', 2, 1);
        $startedGames = $scoreBoard->getGames();

        $this->assertCount(1, $startedGames);
        $this->assertEquals(2, $startedGames[0]['homeScore']);
        $this->assertEquals(1, $startedGames[0]['awayScore']);
        $this->assertEquals(3, $startedGames[0]['totalScore']);
    }

    private function getFinishGameStorageMock(): \PHPUnit\Framework\MockObject\MockObject
    {
        $storage = $this->getMockBuilder(\FWC\ScoreBoard\IStorage::class)->getMock();

        $storage->expects($this->once())
            ->method('get')
            ->with('scoreboard')
            ->willReturn($this->fixture['finishGame']);
        return $storage;
    }

    private function getStartedGameStorageMock(bool $isEmpty = false): \PHPUnit\Framework\MockObject\MockObject
    {
        $storage = $this->getMockBuilder(\FWC\ScoreBoard\IStorage::class)->getMock();

        $storage->expects($this->once())
            ->method('get')
            ->with('scoreboard')
            ->willReturn(!$isEmpty ? $this->fixture['startGame'] : []);

        return $storage;
    }

    private function getUpdateScoreStorageMock(): \PHPUnit\Framework\MockObject\MockObject
    {
        $storage = $this->getMockBuilder(\FWC\ScoreBoard\IStorage::class)->getMock();

        $storage->expects($this->once())
            ->method('get')
            ->with('scoreboard')
            ->willReturn($this->fixture['updateScore']);

        return $storage;
    }
}
