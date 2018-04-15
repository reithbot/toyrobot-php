<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Messaging\Command;

class PlaceRobot
{
    private $coordinates;

    private $direction;

    public function __construct(array $coordinates, string $direction)
    {
        $this->coordinates = $coordinates;
        $this->direction = $direction;
    }

    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    public function getDirection()
    {
        return $this->direction;
    }
}
