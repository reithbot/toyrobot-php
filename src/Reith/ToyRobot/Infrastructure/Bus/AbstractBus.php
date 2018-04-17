<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Infrastructure\Bus;

use Psr\Log\LoggerInterface;
use Assert\Assertion;
use Reith\ToyRobot\Messaging\BusInterface;

abstract class AbstractBus implements BusInterface
{
    // Message handlers
    private $handlers;

    private $logger;

    /**
     * @param LoggerInterface|null $logger
     */
    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param mixed $handler
     */
    public function registerHandler($handler): AbstractBus
    {
        // Expect the handler to be an object
        Assertion::isObject($handler);

        $this->handlers[] = new HandlerWrapper($handler);

        return $this;
    }

    /**
     * @param mixed $message
     *
     * @throws \Assert\AssertionFailedException
     */
    public function post($message): void
    {
        // Expect the message to be an object to use reflection
        // and match the handler method
        Assertion::isObject($message);

        $messageHandler = $this->findHandler($message);

        $this->log(
            sprintf(
                'Sending message [%s] to handler [%s::%s()]', 
                get_class($message),
                get_class($messageHandler[0]),
                $messageHandler[1]
            )
        );

        // Run the command
        call_user_func($messageHandler, $message);
    }

    /**
     * @param string $msg
     */
    private function log(string $msg): void
    {
        if (!$this->logger) {
            return;
        }

        $this->logger->info($msg);
    }

    /**
     * @return callable
     *
     * @throws \RuntimeException
     */
    private function findHandler($message): callable
    {
        // In this implementation the first handler wins but
        // actually we would want to fail if there is more than
        // one for Commands and Queries
        foreach ($this->handlers as $handler) {
            if ($callable = $handler->getCallable($message)) {
                return $callable;
            }
        }

        throw new \RuntimeException(
            sprintf('No handler was found for message [%s]', get_class($message))
        );
    }
}
