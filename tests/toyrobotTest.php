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
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;
use Reith\ToyRobot\Domain\Space\Table;

// Persistence
use Reith\ToyRobot\Infrastructure\Persistence\InMemoryRobotStore;
use Reith\ToyRobot\Infrastructure\Persistence\RobotRepository;

// Console tasks
use Reith\ToyRobot\Console\Place;
use Reith\ToyRobot\Console\Report;
use Reith\ToyRobot\Console\BusHelper;

// Buses
use Reith\ToyRobot\Infrastructure\Bus\CommandBus;
use Reith\ToyRobot\Infrastructure\Bus\QueryBus;

// Command and query handlers
use Reith\ToyRobot\CommandHandler\RobotPlacer;
use Reith\ToyRobot\QueryHandler\RobotReporter;

class toyrobotTest extends TestCase
{
    private $testApp;

    protected function setUp()
    {
        $mockLogger = self::createMock(LoggerInterface::class);

        $store = InMemoryRobotStore::getStore($mockLogger);

        // Pass to the repository
        $repository = new RobotRepository($store);
        $table = Table::create(5);

        // Create the command and query handlers
        $robotPlacer = new RobotPlacer($table, $repository, $mockLogger);
        $robotReporter = new RobotReporter($repository, $mockLogger);

        // Now the buses
        $commandBus = (new CommandBus())
            ->registerHandler($robotPlacer)
        ;

        $queryBus = (new QueryBus())
            ->registerHandler($robotReporter)
        ;

        // Set up the console
        $application = new Application();
        $application->setAutoExit(false);
        $application->addCommands([new Place(), new Report()]);

        $busHelper = (new BusHelper())
            ->setCommandBus($commandBus)
            ->setQueryBus($queryBus)
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

        self::assertSame(0, $this->testApp->getStatusCode(), $this->testApp->getDisplay());
    }

    public function testPlaceRobot()
    {
        $this->testApp->run(['place']);

        self::assertSame(0, $this->testApp->getStatusCode(), $this->testApp->getDisplay());
    }

    public function testRobotReport()
    {
        $this->testApp->run(['report']);

        self::assertSame(0, $this->testApp->getStatusCode(), $this->testApp->getDisplay());
    }

    public function testPlacingAndReporting()
    {
        $instruction = '2,2,S';

        $this->testApp->run(['command' => 'place', 'X,Y,F' => '2,2,S']);

        self::assertSame(0, $this->testApp->getStatusCode(), $this->testApp->getDisplay());

        $this->testApp->run(['report']);

        self::assertContains($instruction, $this->testApp->getDisplay());

        // 
        // An incorrect placement won't move the robot, it'll report
        // the same position
        //
        $this->testApp->run(['command' => 'place', 'X,Y,F' => '12,4,W']);

        self::assertNotEquals(0, $this->testApp->getStatusCode(), $this->testApp->getDisplay());

        $this->testApp->run(['report']);

        self::assertContains($instruction, $this->testApp->getDisplay());
    }
}
