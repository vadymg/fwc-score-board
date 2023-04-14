<?php

require_once __DIR__ . '/../vendor/autoload.php';

$teams = FWC\ScoreBoard\Team::getTeamList();
$storage = new FWC\ScoreBoard\RedisStorage();
$scoreBoard = new FWC\ScoreBoard\ScoreBoard($storage);

$htmlTeamOptions = '';
foreach ($teams as $team) {
    $htmlTeamOptions .= '<option value="' . $team . '">' . $team . '</option>';
}
$error = '';
if (isset($_POST['formType'])) {
    $homeTeam = $_POST['homeTeam'] ?? '';
    $awayTeam = $_POST['awayTeam'] ?? '';
    $homeTeamScore = $_POST['homeTeamScore'] ?? 0;
    $awayTeamScore = $_POST['awayTeamScore'] ?? 0;
    switch ($_POST['formType']) {
        case 'startGame':
            try {
                $scoreBoard->startGame($homeTeam, $awayTeam);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
            break;
        case 'updateScore':
            try {
                $scoreBoard->updateScore($homeTeam, $awayTeam, $homeTeamScore, $awayTeamScore);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
            break;
        case 'finishGame':
            try {
                $scoreBoard->finishGame($homeTeam, $awayTeam);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
            break;
        default:
            $error = 'Invalid form type';
    }
}

if ($error) {
    echo <<<HTML
    <h4 style="color: red">{$error}</h4>
HTML;
}

echo <<<HTML
<form method="post" action="index.php?formType=startGame">
    <lable for="homeTeam">Home Team:</lable>
    <select name="homeTeam">
        {$htmlTeamOptions}
    </select>
    <lable for="awayTeam">Away Team:</lable>
    <select name="awayTeam">
        {$htmlTeamOptions}
    </select>
    <input type="hidden" name="formType" value="startGame" />
    <input type="submit" value="Start A Game" />
</form>
<br />
HTML;
echo <<<HTML
<form method="post" action="index.php?formType=updateScore">
    <lable for="homeTeam">Home Team:</lable>
    <select name="homeTeam">
        {$htmlTeamOptions}
    </select>
    <input type="number" min="0" max="50" value="0" name="homeTeamScore" />
    <lable for="awayTeam">Away Team:</lable>
    <select name="awayTeam">
        {$htmlTeamOptions}
    </select>
    <input type="number" min="0" max="50" value="0" name="awayTeamScore" />
    <input type="hidden" name="formType" value="updateScore" />
    <input type="submit" value="Update score" />
</form>
<br />
HTML;
echo <<<HTML
<form method="post" action="index.php?formType=finishGame">
    <lable for="homeTeam">Home Team:</lable>
    <select name="homeTeam">
        {$htmlTeamOptions}
    </select>
    <lable for="awayTeam">Away Team:</lable>
    <select name="awayTeam">
        {$htmlTeamOptions}
    </select>
    <input type="hidden" name="formType" value="finishGame" />
    <input type="submit" value="Finish Game" />
</form>
HTML;

$games = $scoreBoard->getGames();
if ($games) {
    echo <<<HTML
    <h3>Score Board</h3>
    <ol>
HTML;
    foreach ($games as $game) {
        echo <<<HTML
        <li>{$game['homeTeam']} {$game['homeScore']} - {$game['awayTeam']} {$game['awayScore']}</li>
HTML;
    }
    echo <<<HTML
    </ol>
HTML;
}
