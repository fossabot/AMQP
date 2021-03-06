<?php
declare(strict_types = 1);

namespace Innmind\AMQP\Model\Basic\Message;

/**
 * Use this property in case you want to reference the message in your application
 */
final class Id
{
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
