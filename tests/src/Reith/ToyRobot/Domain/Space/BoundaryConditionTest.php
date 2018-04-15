<?php
/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Domain\Space;

use PHPUnit\Framework\TestCase;

class BoundaryConditionTest extends TestCase
{
    /**
     * @dataProvider getPassingBounds
     */
    public static function testPositiveBoundaryConditions($bounds, $value)
    {
        // For a 10 x 10 space, for example
        $condition = BoundaryCondition::create($bounds);

        self::assertInstanceOf(BoundaryCondition::class, $condition);
        self::assertTrue($condition->test($value));
    }

    public function getPassingBounds(): array
    {
        return [
            // 10 would be part of a 10 x 10 space, 8 falls within it
            // bounds, value to check
            [10, 8],
            [10, 0],
            [10, 9],
            [10, 1],
            [100, 99],
            [1, 0],
            [23, 16],
        ];
    }

    /**
     * @dataProvider getFailingBounds
     * @expectedException \Reith\ToyRobot\Domain\Space\Exception\BoundaryTestException
     */
    public static function testNegativeBoundaryConditions($bounds, $value)
    {
        // For a 10 x 10 space, for example
        $condition = BoundaryCondition::create($bounds);

        self::assertInstanceOf(BoundaryCondition::class, $condition);
        self::assertTrue($condition->test($value));
    }

    public function getFailingBounds(): array
    {
        return [
            [10, 10],
            [10, -1],
            [100, 1000000],
            [1, 1],
            [1, -100],
        ];
    }
}
