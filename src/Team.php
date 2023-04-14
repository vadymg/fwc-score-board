<?php

namespace FWC\ScoreBoard;

class Team
{
    public static function inTeamList(string $team): bool
    {
        return in_array($team, self::getTeamList());
    }

    public static function getTeamList(): array
    {
        return ['Mexico', 'Canada', 'Spain', 'Brazil', 'Germany', 'France', 'Uruguay', 'Italy', 'Argentina', 'Australi'];
    }
}
