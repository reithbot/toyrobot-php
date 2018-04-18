<?php
/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Domain\Space;

use MathPHP\LinearAlgebra\Vector;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    public static function testCreatingATable()
    {
        // For a 10 x 10 table, for example
        $table = Table::create(10);

        self::assertInstanceOf(Table::class, $table);
    }

    /**
     * When moving in a space, the dimensions of the move must
     * be the same dimensionality as the space (in this model).
     *
     * @dataProvider getVectorsNotFitForATable
     * @expectedException \Reith\ToyRobot\Domain\Space\Exception\PlaceDimensionsDoNotMatchSpaceException
     */
    public static function testThatMovementsMustBeOfTheSameDimensionality(Vector $badPlace)
    {
        $table = Table::create(5);
        self::assertInstanceOf(Table::class, $table);

        // If we just pass one Vector to move() $from
        // is assumed to be origin
        $table->move($badPlace);
    }

    public static function getVectorsNotFitForATable(): array
    {
        return [
            [new Vector([1])],
            [new Vector([1, 1, 1])],
            [new Vector([1, 1, 1, 1])],
        ];
    }
}
