<?php

namespace FWC\ScoreBoard;

interface IStorage
{
    public function save(string $key, array $value): void;
    public function get(string $key): array;
}
