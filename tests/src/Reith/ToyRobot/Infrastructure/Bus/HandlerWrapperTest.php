<?php
/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Infrastructure\Bus;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Reith\ToyRobot\Messaging\Command\PlaceRobot;
use Reith\ToyRobot\Domain\Space\Table;
use Reith\ToyRobot\Domain\Robot\RobotRepositoryInterface;
use Reith\ToyRobot\CommandHandler\RobotPlacer;

class HandlerWrapperTest extends TestCase
{
    /**
     * @test
     */
    public function willFindAnnotatedMethods()
    {
        $table = Table::create(10);
        $mockRepo = self::createMock(RobotRepositoryInterface::class);
        $mockLogger = self::createMock(LoggerInterface::class);
        $robotPlacer = new RobotPlacer($table, $mockRepo, $mockLogger);

        $wrapper = new HandlerWrapper($robotPlacer);

        $command = new PlaceRobot([0, 1], 'N');
        $callable = $wrapper->getCallable($command);

        self::assertTrue(is_callable($callable));
    }

    /**
     * @test
     * @expectedException \Assert\AssertionFailedException
     */
    public static function handlerMustHaveMethods()
    {
        $badHandler = new \stdClass();
        new HandlerWrapper($badHandler);
    }
}
