<?php

namespace FWC\ScoreBoard\Services;

use FWC\ScoreBoard\DTO\Team;

class TeamService
{
    /**`
     * @var Team[]
     */
    private array $teams = [];

    public function __construct(array $teams = [])
    {
        $this->makeTeams($teams);
    }

    public function addTeam(string $name): void
    {
        $this->teams[$name] = $this->makeTeam($name);
    }

    public function getTeam(string $name): ?Team
    {
        return $this->teams[$name] ?? null;
    }

    public function getTeams(): array
    {
        return $this->teams;
    }

    private function makeTeams(array $teams): void
    {
        foreach ($teams as $team) {
            $this->addTeam($team);
        }
    }

    private function makeTeam(string $name): Team
    {
        return new Team($name);
    }
}
