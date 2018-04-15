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

class PlaceTest extends TestCase
{
    public static function testCreatingAPlace()
    {
        // Create a placement at 3,2
        $place = Place::create([3, 2]);

        self::assertInstanceOf(Place::class, $place);
    }

    /**
     * @dataProvider provideBadCoordinates
     * @expectedException \Assert\AssertionFailedException
     *
     * @param array $badCoords
     */
    public static function testCoordinates(array $badCoords)
    {
        Place::create($badCoords);
    }

    public static function provideBadCoordinates(): array
    {
        return [
            [[]], // empty
            [['a']], // chars
            [[3, 3, 3, 4.5]], // float
        ];
    }

    /**
     * @test
     */
    public static function canMapPlaceCoords()
    {
        $place = Place::create([1, 2, 3]);
        $result = $place->map(function ($coord) {
            return $coord * 2;
        });

        self::assertEquals([2, 4, 6], $result);
    }
}
