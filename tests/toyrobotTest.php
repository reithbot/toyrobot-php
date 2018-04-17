<?php
/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;
use Reith\ToyRobot\Domain\Space\Table;
// Persistence
use Reith\ToyRobot\Infrastructure\Persistence\FileRobotStore;
use Reith\ToyRobot\Infrastructure\Persistence\RobotRepository;
// Console tasks
use Reith\ToyRobot\Console\Place;
use Reith\ToyRobot\Console\BusHelper;
// Buses
use Reith\ToyRobot\Infrastructure\Bus\CommandBus;
// Command and query handlers
use Reith\ToyRobot\CommandHandler\RobotPlacer;

class toyrobotTest extends TestCase
{
    private $testApp;

    protected function setUp()
    {
        $basePath = vfsStream::setup('basePath');

        $store = FileRobotStore::getStore(vfsStream::url('basePath'));

        // Pass to the repository
        $repository = new RobotRepository($store);
        $table = Table::create(5);

        $mockLogger = self::createMock(LoggerInterface::class);

        // Create the command and query handlers
        $robotPlacer = new RobotPlacer($table, $repository, $mockLogger);

        // Now the buses
        $commandBus = (new CommandBus())
            ->registerHandler($robotPlacer)
        ;

        // Set up the console
        $application = new Application();
        $application->setAutoExit(false);
        $application->add(new Place());

        $busHelper = (new BusHelper())
            ->setCommandBus($commandBus)
        ;
        $application->getHelperSet()->set($busHelper);

        $this->testApp = new ApplicationTester($application);
    }

    public function testToyrobotApp()
    {
        $this->testApp->run([]);

        self::assertContains(
            'Place the robot on the table',
            $this->testApp->getDisplay()
        );

        self::assertSame(0, $this->testApp->getStatusCode());
    }

    public function testPlaceRobot()
    {
        $this->testApp->run(['place']);

        self::assertSame(0, $this->testApp->getStatusCode());
    }
}
