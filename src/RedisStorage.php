<?php

namespace FWC\ScoreBoard;

use Predis\Client;

class RedisStorage implements IStorage
{
    private $redis;

    public function __construct()
    {
        $this->initClient();
    }

    public function save(string $key, array $value): void
    {
        $this->redis->set($key, serialize($value));
    }

    public function get(string $key): array
    {
        if ($data =  $this->redis->get($key)) {
            return unserialize($data);
        }

        return [];
    }

    private function initClient(): void
    {
        if (!$this->redis) {
            $this->redis = new Client([
                'scheme' => 'tcp',
                'host'   => 'redis', // The hostname of the Redis service as defined in the docker-compose.yml file
                'port'   => 6379,    // The port on which Redis is exposed
            ]);
        }
    }
}
