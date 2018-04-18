<?php
/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\QueryHandler;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Reith\ToyRobot\Messaging\Query\RobotReport;
use Reith\ToyRobot\Domain\Space\Table;
use Reith\ToyRobot\Domain\Robot\RobotRepositoryInterface;

class RobotReporterTest extends TestCase
{
    /**
     * @test
     */
    public function willHandlePlaceRobot()
    {
        $mockRepo = self::createMock(RobotRepositoryInterface::class);
        $mockLogger = self::createMock(LoggerInterface::class);
        $robotReporter = new RobotReporter($mockRepo, $mockLogger);

        self::assertInstanceOf(RobotReporter::class, $robotReporter);

        $query = new RobotReport();

        $robot = Table::create(3)->placeRobot();

        $mockRepo->expects($this->once())
            ->method('load')
            ->willReturn($robot);

        $result = $robotReporter->handleReport($query);

        self::assertSame('0,0,E', $result);
    }
}
