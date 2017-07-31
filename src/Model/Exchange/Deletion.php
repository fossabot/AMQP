<?php
declare(strict_types = 1);

namespace Innmind\AMQP\Model\Exchange;

final class Deletion
{
    private $name;
    private $ifUnused = false;
    private $wait = true;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function ifUnused(): self
    {
        $self = clone $this;
        $self->ifUnused = true;

        return $self;
    }

    public function ifUsed(): self
    {
        $self = clone $this;
        $self->ifUnused = false;

        return $self;
    }

    public function dontWait(): self
    {
        $self = clone $this;
        $self->wait = false;

        return $self;
    }

    public function wait(): self
    {
        $self = clone $this;
        $self->wait = true;

        return $self;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function onlyIfUnused(): bool
    {
        return $this->ifUnused;
    }

    public function shouldWait(): bool
    {
        return $this->wait;
    }
}
