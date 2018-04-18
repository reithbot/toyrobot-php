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
use Reith\ToyRobot\Messaging\Command\MoveForward;

class Move extends Command
{
    protected function configure()
    {
        $this
            ->setName('MOVE')
            ->setDescription('Move the robot forward')
            ->setHelp(<<<EOT
The <info>MOVE</info> instruction will tell the robot to move forward.

<info>./toyrobot</info> <comment>MOVE</comment>

EOT
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = new MoveForward();

        $this->getHelper('bus')->postCommand($message);

        $this->getApplication()
            ->find('REPORT')
            ->run($input, $output);
    }
}
