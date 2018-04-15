<?php
/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Console;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Reith\ToyRobot\Infrastructure\Bus\CommandBus;

class PlaceTest extends TestCase
{
    public static function testValidInstructions()
    {
        $application = new Application();
        $application->add(new Place());
        $command = $application->find('place');
        $commandTester = new CommandTester($command);
        $busHelper = new BusHelper(new CommandBus([]), new CommandBus([]));
        $application->getHelperSet()->set($busHelper);

        $result = $commandTester->execute([
            'command' => $command->getName(),
            'X,Y,F' => null,
        ]);

        // Successful exit
        self::assertEquals(0, $result);

        $result = $commandTester->execute([
            'command' => $command->getName(),
            'X,Y,F' => '0,1',
        ]);

        self::assertEquals(0, $result);

        $result = $commandTester->execute([
            'command' => $command->getName(),
            'X,Y,F' => '3,2,S',
        ]);

        self::assertEquals(0, $result);
    }

    /**
     * @param string $invalidInstruction
     * @dataProvider getInvalidInstructions
     * @expectedException \RuntimeException
     */
    public static function testInvalidInstructions(string $invalidInstruction)
    {
        $application = new Application();
        $application->add(new Place());
        $command = $application->find('place');
        $commandTester = new CommandTester($command);
        $busHelper = new BusHelper(new CommandBus([]), new CommandBus([]));
        $application->getHelperSet()->set($busHelper);

        $result = $commandTester->execute([
            'command' => $command->getName(),
            'X,Y,F' => $invalidInstruction,
        ]);
    }

    public static function getInvalidInstructions(): array
    {
        return [
            ['5,1'], // out of bounds
            ['bar'], // nonsense
            ['3,2,T'], // not a direction
            ['3,2,South'], // direction is a single letter
            ['2, 2'], // spaces are not allowed
            ['E,1,1'], // wrong order
        ];
    }
}
