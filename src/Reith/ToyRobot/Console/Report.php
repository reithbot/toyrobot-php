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
use Reith\ToyRobot\Messaging\Query\RobotReport;

class Report extends Command
{
    protected function configure()
    {
        $this
            ->setName('REPORT')
            ->setDescription('Get a report from the robot')
            ->setHelp(<<<EOT
The <info>robot:report</info> will get a report from the robot;

<info>./toyrobot</info> <comment>REPORT</comment>

EOT
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = new RobotReport();

        $onSuccess = function (string $result) use ($output) {
            $output->writeln($result);
        };

        $this->getHelper('bus')->postQuery($message, $onSuccess);
    }
}
