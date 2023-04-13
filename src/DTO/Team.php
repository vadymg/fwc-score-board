<?php

namespace FWC\ScoreBoard\DTO;

class Team implements \JsonSerializable
{
    public string $id;
    public string $name;

    public function __construct(string $name)
    {
        $this->id = uniqid();
        $this->name = $name;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
