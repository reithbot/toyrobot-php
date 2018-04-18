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
use Reith\ToyRobot\Console\Left;
use Reith\ToyRobot\Console\Right;
use Reith\ToyRobot\Console\Move;
use Reith\ToyRobot\Console\BusHelper;

// Buses
use Reith\ToyRobot\Infrastructure\Bus\CommandBus;
use Reith\ToyRobot\Infrastructure\Bus\QueryBus;

// Command and query handlers
use Reith\ToyRobot\CommandHandler\RobotPlacer;
use Reith\ToyRobot\CommandHandler\RobotMover;
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
        $robotMover = new RobotMover($repository, $mockLogger);
        $robotReporter = new RobotReporter($repository, $mockLogger);

        // Create the busses and register the handlers
        $commandBus = (new CommandBus())
            ->registerHandler($robotPlacer)
            ->registerHandler($robotMover)
        ;

        $queryBus = (new QueryBus())
            ->registerHandler($robotReporter)
        ;

        // Set up the console
        $application = new Application();
        $application->setAutoExit(false);
        $application->addCommands([
            new Place(),
            new Report(),
            new Left(),
            new Right(),
            new Move(),
        ]);

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

        $this->testApp->run(['command' => 'place', 'X,Y,F' => $instruction]);

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

    public function testTurningLeft()
    {
        $instruction = '3,2,W';

        $this->testApp->run(['command' => 'place', 'X,Y,F' => $instruction]);

        self::assertSame(0, $this->testApp->getStatusCode(), $this->testApp->getDisplay());

        $this->testApp->run(['left']);

        self::assertSame(0, $this->testApp->getStatusCode(), $this->testApp->getDisplay());

        // Now face south
        self::assertContains('3,2,S', $this->testApp->getDisplay());
    }

    public function testTurningRight()
    {
        $instruction = '1,2,E';

        $this->testApp->run(['command' => 'place', 'X,Y,F' => $instruction]);

        self::assertSame(0, $this->testApp->getStatusCode(), $this->testApp->getDisplay());

        $this->testApp->run(['right']);

        self::assertSame(0, $this->testApp->getStatusCode(), $this->testApp->getDisplay());

        // Now face south
        self::assertContains('1,2,S', $this->testApp->getDisplay());
    }

    public function testMovingForward()
    {
        $instruction = '3,3,W';

        $this->testApp->run(['command' => 'place', 'X,Y,F' => $instruction]);

        self::assertSame(0, $this->testApp->getStatusCode(), $this->testApp->getDisplay());

        $this->testApp->run(['move']);

        self::assertSame(0, $this->testApp->getStatusCode(), $this->testApp->getDisplay());

        // Now x has decreased
        self::assertContains('2,3,W', $this->testApp->getDisplay());
    }
}
