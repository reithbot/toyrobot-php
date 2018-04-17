<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Infrastructure\Persistence;

use Reith\ToyRobot\Domain\Robot\RobotRepositoryInterface;
use Reith\ToyRobot\Domain\Robot\RobotFinderInterface;
use Reith\ToyRobot\Domain\Robot\Robot;

class RobotRepository implements RobotRepositoryInterface, RobotFinderInterface
{
    private $store;

    /**
     * @param RobotStoreInterface $file
     */
    public function __construct(RobotStoreInterface $store)
    {
        $this->store = $store;
    }

    /**
     * @return Robot|null
     */
    public function load(): ?Robot
    {
        return $this->store->getRobot();
    }

    /**
     * @param Robot $robot
     */
    public function save(Robot $robot): void
    {
        $this->store->saveRobot($robot);
    }

    /**
     * Implemented for the RobotFinderInterface.
     *
     * @return Robot[]
     */
    public function find(): array
    {
        return [$this->load()];
    }
}
