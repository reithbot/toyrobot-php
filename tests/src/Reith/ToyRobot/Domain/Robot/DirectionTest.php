<?php
/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Domain\Robot;

use PHPUnit\Framework\TestCase;

class DirectionTest extends TestCase
{
    public static function testGettingDirection()
    {
        $direction = new Direction();
        self::assertInstanceOf(Direction::class, $direction);

        $compassPosition = $direction->getDirectionAsString();

        self::assertEquals('E', $compassPosition);
    }

    public static function testRotatingLeftAndRight()
    {
        $direction = new Direction();
        self::assertEquals('E', $direction->getDirectionAsString());

        // Counter clockwise
        $direction->rotateLeft();
        self::assertEquals('N', $direction->getDirectionAsString());

        // Clockwise 180deg
        $direction->rotateRight()->rotateRight();
        self::assertEquals('S', $direction->getDirectionAsString());

        $direction->rotateRight() // 'W'
            ->rotateLeft() // 'S'
            ->rotateLeft() // 'E'
            ->rotateLeft() // 'N'
            ->rotateLeft(); // 'W'

        self::assertEquals('W', $direction->getDirectionAsString());
    }
}
