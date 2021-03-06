<?php
declare(strict_types = 1);

namespace Innmind\AMQP\Model\Basic;

final class Get
{
    private $queue;
    private $ack = true;

    public function __construct(string $queue)
    {
        $this->queue = $queue;
    }

    public function manualAcknowledge(): self
    {
        $self = clone $this;
        $self->ack = true;

        return $self;
    }

    public function autoAcknowledge(): self
    {
        $self = clone $this;
        $self->ack = false;

        return $self;
    }

    public function queue(): string
    {
        return $this->queue;
    }

    public function shouldAutoAcknowledge(): bool
    {
        return !$this->ack;
    }
}
