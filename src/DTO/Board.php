<?php

namespace FWC\ScoreBoard\DTO;

class Board implements \JsonSerializable
{
    public string $id;
    public string $name;

    /**
     * @var Game[]
     */
    public array $games;

    public function __construct(string $name)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->games = [];
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'games' => $this->games,
        ];
    }

    public static function getBoards(): array
    {
        return ['World Cup', 'Olympics', 'Euro Cup'];
    }
}
