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
use Reith\ToyRobot\Domain\Space\Table;

class RobotTest extends TestCase
{
    public static function testCreatingARobot()
    {
        // This is an example of a 5x5 table
        $table = Table::create(5);

        // To create (or reset) a robot it must be placed in a space.
        // Default placement is 0,0 (for 2 dimensions)
        $robot = $table->placeRobot();

        self::assertInstanceOf(Robot::class, $robot);
    }

    /**
     * @test
     */
    public static function robotCanMove()
    {
        $table = Table::create(5);
        $robot = $table->placeRobot();

        // From origin, 0,0, robot can move northward
        $place = $robot->moveNorthward()->getPlacement();

        // Now it should be at 0,1
        self::assertEquals([0, 1], $place->getVector());

        $place = $robot->moveSouthward()->getPlacement();

        // Now it should be back at origin
        self::assertEquals([0, 0], $place->getVector());

        // Let's get it dancing
        $place = $robot
            ->moveEastward()
            ->moveEastward()
            ->moveNorthward()
            ->moveNorthward()
            ->moveWestward()
            ->moveNorthward()
            ->getPlacement()
        ;

        self::assertEquals([1, 3], $place->getVector());
    }

    /**
     * @test
     * @expectedException \Assert\AssertionFailedException
     */
    public static function robotCannotStartWithABadDirection()
    {
        $table = Table::create(3);

        Robot::create(
            Table::create(4),
            Place::create([0, 1]),
            'U'
        );
    }

    /**
     * @test
     * @expectedException \Reith\ToyRobot\Domain\Space\Exception\BoundaryTestException
     */
    public static function robotCannotFallOffTheSouthSide()
    {
        $table = Table::create(5);
        $robot = $table->placeRobot();

        $robot->moveSouthward();
    }

    /**
     * @test
     * @expectedException \Reith\ToyRobot\Domain\Space\Exception\BoundaryTestException
     */
    public static function robotCannotFallOffTheWestSide()
    {
        $table = Table::create(5);
        $robot = $table->placeRobot();

        $robot->moveWestward();
    }

    /**
     * @test
     * @expectedException \Reith\ToyRobot\Domain\Space\Exception\BoundaryTestException
     */
    public static function robotCannotFallOffTheNorthSide()
    {
        // Smaller 3 x 3 table
        $table = Table::create(3);
        $robot = $table->placeRobot();

        $robot
            ->moveNorthward()
            ->moveNorthward()
            ->moveNorthward()
        ;
    }
}
