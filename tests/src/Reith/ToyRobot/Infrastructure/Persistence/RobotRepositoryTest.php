<?php
/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Infrastructure\Persistence;

use PHPUnit\Framework\TestCase;
use Reith\ToyRobot\Domain\Space\Table;

class RobotRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function willReturnARobot()
    {
        $robot = Table::create(5)->placeRobot();

        $mockStore = self::createMock(RobotStoreInterface::class);
        $mockStore->method('getRobot')
            ->willReturn($robot);

        $repository = new RobotRepository($mockStore);

        self::assertInstanceOf(RobotRepository::class, $repository);
        self::assertSame($robot, $repository->load());
    }
}
