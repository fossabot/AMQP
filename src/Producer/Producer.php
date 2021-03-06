<?php
declare(strict_types = 1);

namespace Innmind\AMQP\Producer;

use Innmind\AMQP\{
    Client,
    Producer as ProducerInterface,
    Model\Basic\Publish,
    Model\Basic\Message,
};
final class Producer implements ProducerInterface
{
    private $client;
    private $exchange;

    public function __construct(Client $client, string $exchange)
    {
        $this->client = $client;
        $this->exchange = $exchange;
    }

    public function __invoke(Message $message, string $routingKey = null): ProducerInterface
    {
        $this
            ->client
            ->channel()
            ->basic()
            ->publish(
                (new Publish($message))
                    ->to($this->exchange)
                    ->withRoutingKey($routingKey ?? '')
            );

        return $this;
    }
}
