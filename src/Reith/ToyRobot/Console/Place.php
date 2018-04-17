<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Reith\ToyRobot\Messaging\Command\PlaceRobot;

class Place extends Command
{
    private const ARG = 'X,Y,F';

    private const DEFAULT_INSTRUCTION = '0,0,E';

    protected function configure()
    {
        $argMsg = 'The optional placement instructions. [X,Y] is the position. [F] is the direction: [N|E|S|W]';

        $this
            ->setName('PLACE')
            ->setDescription('Place the robot on the table')
            ->addArgument(self::ARG, InputArgument::OPTIONAL, $argMsg)
            ->setHelp(<<<EOT
The <info>robot:place</info> will set a robot on the table.

<info>./toyrobot</info> <comment>PLACE X,Y,F</comment>

<comment>[X,Y]</comment> is the coordinates of the starting position Robot, default is [0,0]
<comment>[F]</comment> is the direction the robot is facing, default is [E]ast

e.g.
    `./toyrobot PLACE`          to place a robot at [0,0] facing East.
    `./toyrobot PLACE 1,1`      to place a robot at [1,1] facing East.
    `./toyrobot PLACE 2,3,S`    to place a robot at [2,3] facing South.

EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $instruction = $input->getArgument(self::ARG) ?: self::DEFAULT_INSTRUCTION;

        $validInstructionRegEx = '/^[0-4],[0-4](,[NESW])?$/';

        if (1 !== preg_match($validInstructionRegEx, $instruction)) {
            throw new \RuntimeException(
                sprintf('Instruction [%s] is not valid', $instruction)
            );
        }

        $parts = explode(',', $instruction);

        [$x, $y] = $parts;

        $direction = 3 === count($parts) ? $parts[2] : 'E';

        $output->writeln(
            sprintf('Using instruction [%d,%d,%s]', $x, $y, $direction)
        );

        $message = new PlaceRobot([$x, $y], $direction);

        $this->getHelper('bus')->postCommand($message);
    }
}
