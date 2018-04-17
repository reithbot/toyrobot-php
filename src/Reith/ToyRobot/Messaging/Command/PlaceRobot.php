<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Messaging\Command;

use Assert\Assertion;

class PlaceRobot
{
    private $coordinates;

    private $direction;

    /**
     * @param array  $coordinates
     * @param string $direction
     */
    public function __construct(array $coordinates, string $direction)
    {
        $this->setCoordinates($coordinates);
        $this->setDirection($direction);
    }

    /**
     * @param array $coordinates
     */
    private function setCoordinates(array $coordinates): void
    {
        // Sanitize command input, ensure
        // int[]
        $this->coordinates = array_map(
            function ($coord) {
                Assertion::numeric($coord);

                return (int) $coord;
            },
            $coordinates
        );
    }

    /**
     * @param string $direction
     */
    private function setDirection(string $direction): void
    {
        // Sanitize direction, 1 char capital
        $direction = strtoupper(trim($direction));
        Assertion::length($direction, 1);
        Assertion::choice($direction, ['N', 'E', 'S', 'W']);
        $this->direction = $direction;
    }

    public function getCoordinates(): array
    {
        return $this->coordinates;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}
