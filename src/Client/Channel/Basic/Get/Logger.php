<?php
declare(strict_types = 1);

namespace Innmind\AMQP\Client\Channel\Basic\Get;

use Innmind\AMQP\{
    Client\Channel\Basic\Get,
    Model\Basic\Message,
    Exception\Reject,
    Exception\Requeue,
};
use Psr\Log\LoggerInterface;

final class Logger implements Get
{
    private $get;
    private $logger;

    public function __construct(
        Get $get,
        LoggerInterface $logger
    ) {
        $this->get = $get;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(callable $consume): void
    {
        ($this->get)(function(Message $message, ...$args) use ($consume): void {
            try {
                $this->logger->debug(
                    'AMQP message received',
                    ['body' => (string) $message->body()]
                );

                $consume($message, ...$args);
            } catch (Reject $e) {
                $this->logger->warning(
                    'AMQP message rejected',
                    ['body' => (string) $message->body()]
                );
                throw $e;
            } catch (Requeue $e) {
                $this->logger->info(
                    'AMQP message requeued',
                    ['body' => (string) $message->body()]
                );
                throw $e;
            } catch (\Throwable $e) {
                $this->logger->error(
                    'AMQP message consumption generated an exception',
                    [
                        'body' => (string) $message->body(),
                        'exception' => \get_class($e),
                    ]
                );
                throw $e;
            }
        });
    }
}
