<?php
/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Domain\Robot;

use MathPHP\LinearAlgebra\Vector;
use PHPUnit\Framework\TestCase;
use Reith\ToyRobot\Domain\Space\Table;

class RobotTest extends TestCase
{
    public static function testCreatingARobot()
    {
        // This is an example of a 5x5 table
        $table = Table::create(5);

        // To create (or reset) a robot it must be placed in a space.
        // Default position is 0,0 (for 2 dimensions)
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
        $position = $robot->moveNorthward()->getPosition();

        // Now it should be at 0,1
        self::assertEquals([0, 1], $position->getVector());

        $position = $robot->moveSouthward()->getPosition();

        // Now it should be back at origin
        self::assertEquals([0, 0], $position->getVector());

        // Let's get it dancing
        $position = $robot
            ->moveEastward()
            ->moveEastward()
            ->moveNorthward()
            ->moveNorthward()
            ->moveWestward()
            ->moveNorthward()
            ->getPosition()
        ;

        self::assertEquals([1, 3], $position->getVector());

        self::assertSame('1,3,N', $robot->getReportAsString());
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
            new Vector([0, 1]),
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
