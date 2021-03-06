<?php
declare(strict_types = 1);

namespace Tests\Innmind\AMQP\Client\Channel\Basic\Get;

use Innmind\AMQP\{
    Client\Channel\Basic\Get\Logger,
    Client\Channel\Basic\Get,
    Model\Basic\Message,
    Exception\Reject,
    Exception\Requeue,
};
use Innmind\Immutable\Str;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Get::class,
            new Logger(
                $this->createMock(Get::class),
                $this->createMock(LoggerInterface::class)
            )
        );
    }

    public function testLogMessageReceived()
    {
        $message = $this->createMock(Message::class);
        $message
            ->method('body')
            ->willReturn(new Str('foobar'));

        $consume = new Logger(
            new class($message) implements Get {
                private $message;

                public function __construct($message)
                {
                    $this->message = $message;
                }

                public function __invoke(callable $consume): void
                {
                    $consume($this->message);
                }
            },
            $logger = $this->createMock(LoggerInterface::class)
        );
        $logger
            ->expects($this->once())
            ->method('debug')
            ->with(
                'AMQP message received',
                ['body' => 'foobar']
            );

        $consume(function(){});
    }

    public function testLogReject()
    {
        $message = $this->createMock(Message::class);
        $message
            ->method('body')
            ->willReturn(new Str('foobar'));

        $consume = new Logger(
            new class($message) implements Get {
                private $message;

                public function __construct($message)
                {
                    $this->message = $message;
                }

                public function __invoke(callable $consume): void
                {
                    try {
                        $consume($this->message);
                    } catch (Reject $e) {
                        //pass
                    }
                }
            },
            $logger = $this->createMock(LoggerInterface::class)
        );
        $logger
            ->expects($this->once())
            ->method('warning')
            ->with(
                'AMQP message rejected',
                ['body' => 'foobar']
            );

        $consume(function(){
            throw new Reject;
        });
    }

    public function testLogRequeue()
    {
        $message = $this->createMock(Message::class);
        $message
            ->method('body')
            ->willReturn(new Str('foobar'));

        $consume = new Logger(
            new class($message) implements Get {
                private $message;

                public function __construct($message)
                {
                    $this->message = $message;
                }

                public function __invoke(callable $consume): void
                {
                    try {
                        $consume($this->message);
                    } catch (Requeue $e) {
                        //pass
                    }
                }
            },
            $logger = $this->createMock(LoggerInterface::class)
        );
        $logger
            ->expects($this->once())
            ->method('info')
            ->with(
                'AMQP message requeued',
                ['body' => 'foobar']
            );

        $consume(function(){
            throw new Requeue;
        });
    }

    public function testLogError()
    {
        $message = $this->createMock(Message::class);
        $message
            ->method('body')
            ->willReturn(new Str('foobar'));

        $consume = new Logger(
            new class($message) implements Get {
                private $message;

                public function __construct($message)
                {
                    $this->message = $message;
                }

                public function __invoke(callable $consume): void
                {
                    try {
                        $consume($this->message);
                    } catch (\RuntimeException $e) {
                        //pass
                    }
                }
            },
            $logger = $this->createMock(LoggerInterface::class)
        );
        $logger
            ->expects($this->once())
            ->method('error')
            ->with(
                'AMQP message consumption generated an exception',
                [
                    'body' => 'foobar',
                    'exception' => 'RuntimeException'
                ]
            );

        $consume(function(){
            throw new \RuntimeException;
        });
    }
}
