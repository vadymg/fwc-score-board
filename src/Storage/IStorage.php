<?php

namespace FWC\ScoreBoard\Storage;

interface IStorage
{
    public function save(string $data): void;
    public function load(): string;
}
