<?php
declare(strict_types = 1);

namespace Innmind\AMQP;

use Innmind\Socket\Internet\Transport as Socket;
use Innmind\Url\UrlInterface;
use Innmind\TimeContinuum\{
    TimeContinuumInterface,
    ElapsedPeriod,
};
use Innmind\CLI\Command as CLICommand;
use Innmind\OperatingSystem\{
    CurrentProcess,
    Remote,
};
use Innmind\Immutable\{
    SetInterface,
    MapInterface,
};
use Psr\Log\LoggerInterface;

function bootstrap(LoggerInterface $logger = null): array
{
    return [
        'client' => [
            'basic' => static function(
                Socket $transport,
                UrlInterface $server,
                ElapsedPeriod $timeout,
                TimeContinuumInterface $clock,
                CurrentProcess $process,
                Remote $remote
            ) use (
                $logger
            ): Client {
                $connection = new Transport\Connection\Lazy(
                    $transport,
                    $server,
                    new Transport\Protocol\v091\Protocol(
                        new Transport\Protocol\ArgumentTranslator\ValueTranslator
                    ),
                    $timeout,
                    $clock,
                    $remote
                );

                if ($logger instanceof LoggerInterface) {
                    $connection = new Transport\Connection\Logger($connection, $logger);
                }

                return new Client\Client($connection, $process);
            },
            'fluent' => static function(Client $client): Client {
                return new Client\Fluent($client);
            },
            'logger' => static function(Client $client) use ($logger): Client {
                return new Client\Logger($client, $logger);
            },
            'signal_aware' => static function(Client $client): Client {
                return new Client\SignalAware($client);
            },
            'auto_declare' => static function(SetInterface $exchanges, SetInterface $queues, SetInterface $bindings): callable {
                return static function(Client $client) use ($exchanges, $queues, $bindings): Client {
                    return new Client\AutoDeclare($client, $exchanges, $queues, $bindings);
                };
            },
        ],
        'command' => [
            'purge' => static function(Client $client): CLICommand {
                return new Command\Purge($client);
            },
            'get' => static function(MapInterface $consumers): callable {
                return static function(Client $client) use ($consumers): CLICommand {
                    return new Command\Get($client, new Consumers($consumers));
                };
            },
            'consume' => static function(MapInterface $consumers): callable {
                return static function(Client $client) use ($consumers): CLICommand {
                    return new Command\Consume($client, new Consumers($consumers));
                };
            },
        ],
        'producers' => static function(SetInterface $exchanges): callable {
            return static function(Client $client) use ($exchanges): Producers {
                return Producers::fromDeclarations($client, ...$exchanges);
            };
        },
    ];
}
