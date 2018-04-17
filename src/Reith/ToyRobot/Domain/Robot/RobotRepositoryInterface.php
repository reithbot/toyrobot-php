<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Domain\Robot;

interface RobotRepositoryInterface
{
    /**
     * @return Robot|null
     */
    public function load(): ?Robot;

    /**
     * @param Robot $robot
     */
    public function save(Robot $robot): void;
}
