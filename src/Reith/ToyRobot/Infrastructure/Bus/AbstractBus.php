<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Infrastructure\Bus;

use Assert\Assertion;
use Reith\ToyRobot\Messaging\BusInterface;

abstract class AbstractBus implements BusInterface
{
    // Message handlers
    private $handlers;

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
     * @throws \Assert\AssertionFailedException
     */
    public function post($message): void
    {
        // Expect the message to be an object to use reflection
        // and match the handler method
        Assertion::isObject($message);

        $messageHandler = $this->findHandler($message);

        // Run the command
        call_user_func($messageHandler, $message);
    }

    /**
     * @return callable
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
