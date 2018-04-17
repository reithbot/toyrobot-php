<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Infrastructure\Persistence;

use Reith\ToyRobot\Domain\Robot\Robot;

interface RobotStoreInterface
{
    public function getRobot(): ?Robot;

    public function saveRobot(Robot $robot): void;
}
