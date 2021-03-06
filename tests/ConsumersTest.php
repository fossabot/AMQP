<?php
declare(strict_types = 1);

namespace Tests\Innmind\AMQP;

use Innmind\AMQP\Consumers;
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class ConsumersTest extends TestCase
{
    public function testInterface()
    {
        $consumers = new Consumers(
            (new Map('string', 'callable'))
                ->put('foo', $expected = function(){})
        );

        $this->assertTrue($consumers->contains('foo'));
        $this->assertFalse($consumers->contains('bar'));
        $this->assertSame($expected, $consumers->get('foo'));
    }

    public function testMapIsOptional()
    {
        $consumers = new Consumers;

        $this->assertFalse($consumers->contains('foo'));
    }

    public function testThrowWhenInvaliMapKey()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 1 must be of type MapInterface<string, callable>');

        new Consumers(new Map('int', 'callable'));
    }

    public function testThrowWhenInvaliMapValue()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 1 must be of type MapInterface<string, callable>');

        new Consumers(new Map('string', 'string'));
    }
}
