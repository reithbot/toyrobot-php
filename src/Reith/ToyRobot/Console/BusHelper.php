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

    public function __construct(BusInterface $commandBus, BusInterface $queryBus)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function setHelperSet(HelperSet $helperSet = null)
    {
        $this->helperSet = $helperSet;
    }

    /**
     * Gets the helper set associated with this helper.
     *
     * @return HelperSet A HelperSet instance
     */
    public function getHelperSet()
    {
        return $this->getHelperSet;
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

    public function postCommand($message)
    {
        $this->commandBus->post($message);
    }

    public function postQuery($message)
    {
        $this->queryBus->post($message);
    }
}
