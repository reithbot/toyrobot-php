<?php
/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\CommandHandler;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Reith\ToyRobot\Messaging\Command\PlaceRobot;
use Reith\ToyRobot\Domain\Space\Table;
use Reith\ToyRobot\Domain\Robot\RobotRepositoryInterface;
use Reith\ToyRobot\Domain\Robot\Robot;

class RobotPlacerTest extends TestCase
{
    /**
     * @test
     */
    public function willHandlePlaceRobot()
    {
        $table = Table::create(10);
        $mockRepo = self::createMock(RobotRepositoryInterface::class);
        $mockLogger = self::createMock(LoggerInterface::class);
        $robotPlacer = new RobotPlacer($table, $mockRepo, $mockLogger);

        self::assertInstanceOf(RobotPlacer::class, $robotPlacer);

        $command = new PlaceRobot([1, 4], 'W');

        $localRobot = null;

        $mockRepo->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(function (Robot $robot) use (&$localRobot) {
                    $localRobot = $robot;

                    return true;
                })
            );

        $robotPlacer->handlePlaceRobot($command);

        self::assertInstanceOf(Robot::class, $localRobot);

        self::assertSame('1,4,W', $localRobot->getReportAsString());
    }
}
