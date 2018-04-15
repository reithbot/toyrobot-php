<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Infrastructure\Bus;

use Reith\ToyRobot\Messaging\BusInterface;
use Reith\ToyRobot\Messaging\MessageInterface;

class CommandBus implements BusInterface
{
    private $handlers;

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    public function post($message): void
    {
        // do some posting
    }
}
