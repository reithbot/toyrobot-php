<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Console;

use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Reith\ToyRobot\Messaging\BusInterface;

class BusHelper implements HelperInterface
{
    private $helperSet;

    private $commandBus;

    private $queryBus;

    public function setCommandBus(BusInterface $commandBus): BusHelper
    {
        $this->commandBus = $commandBus;

        return $this;
    }

    public function setQueryBus(BusInterface $queryBus): BusHelper
    {
        $this->queryBus = $queryBus;

        return $this;
    }

    public function setHelperSet(HelperSet $helperSet = null)
    {
        $this->helperSet = $helperSet;

        return $this;
    }

    /**
     * Gets the helper set associated with this helper.
     *
     * @return HelperSet A HelperSet instance
     */
    public function getHelperSet()
    {
        return $this->helperSet;
    }

    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
        return 'bus';
    }

    /**
     * @param mixed $message
     *
     * @throws \RuntimeException
     */
    public function postCommand($message): void
    {
        if (!$this->commandBus) {
            throw new \RuntimeException(
                'Command bus is not configured'
            );
        }

        $this->commandBus->post($message);
    }

    /**
     * @param mixed     $message
     * @param ?callable $callback To get response data
     *
     * @throws \RuntimeException
     */
    public function postQuery($message, ?callable $callback = null): void
    {
        if (!$this->queryBus) {
            throw new \RuntimeException(
                'Query bus is not configured'
            );
        }

        $this->queryBus->post($message, $callback);
    }
}
