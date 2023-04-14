<?php

namespace FWC\ScoreBoard\Tests;

use FWC\ScoreBoard\RedisStorage;

/**
 * @covers \FWC\ScoreBoard\RedisStorage
 */
class RedisStorageTest extends \PHPUnit\Framework\TestCase
{
    public function testSaveAndGet()
    {
        /** @var \FWC\ScoreBoard\IStorage | \PHPUnit\Framework\MockObject\MockObject $storage */
        $storage = $this->getMockBuilder(RedisStorage::class)->getMock();

        $storage->expects($this->once())
            ->method('save')
            ->with('test', ['test']);
        $storage->expects($this->once())
            ->method('get')
            ->with('test')
            ->willReturn(['test']);

        $storage->save('test', ['test']);
        $this->assertEquals(['test'], $storage->get('test'));
    }
}
