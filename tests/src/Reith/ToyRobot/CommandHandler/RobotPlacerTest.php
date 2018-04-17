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

        $command = new PlaceRobot([0, 1], 'N');

        $mockRepo->expects($this->once())->method('save');

        $robotPlacer->handlePlaceRobot($command);
    }
}
