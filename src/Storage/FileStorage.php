<?php

namespace FWC\ScoreBoard\Storage;

class FileStorage implements IStorage
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function load(): string
    {
        if (!file_exists($this->filename) || filesize($this->filename) === 0) {
            return '';
        }
        $file = fopen($this->filename, 'r');

        $data = fread($file, filesize($this->filename));
        fclose($file);

        return $data;
    }

    public function save(string $data): void
    {
        $file = fopen($this->filename, 'w');
        fwrite($file, $data);
        fclose($file);
    }
}
