<?php

require_once __DIR__ . '/../vendor/autoload.php';

use FWC\ScoreBoard\Services\BoardResultService;
use FWC\ScoreBoard\Services\BoardService;
use FWC\ScoreBoard\Services\GameService;
use FWC\ScoreBoard\Services\TeamService;
use FWC\ScoreBoard\Storage\FileStorage;

$fileStorage = new FileStorage('data.txt');
$teamService =  new TeamService(['Mexico', 'Canada', 'Spain', 'Brazil', 'Germany', 'France', 'Uruguay', 'Italy', 'Argentina', 'Australi']);
$gameService = new GameService($teamService);
$boardService = new BoardService($gameService, $fileStorage);
$boardService->startGame('Mexico', 'Canada');
$boardService->startGame('Spain', 'Brazil');
$boardService->startGame('Germany', 'France');
$boardService->startGame('Uruguay', 'Italy');
$boardService->startGame('Argentina', 'Australi');

foreach ($boardService->getGames() as $game) {
    $boardService->updateScoreById($game->id, rand(0, 10), rand(0, 10));
}

$boardResultService = new BoardResultService($boardService);
$boardResult = $boardResultService->getBoardResult();

foreach ($boardResult['games'] as $game) {
    echo '<pre>';
    echo $game['home'] . ' ' . $game['homeScore']  . ' - ' . $game['away'] . ' ' . $game['awayScore'];
    echo '</pre>';
}
