<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\QueryHandler;

use Psr\Log\LoggerInterface;
use Reith\ToyRobot\Domain\Robot\Robot;
use Reith\ToyRobot\Domain\Robot\RobotRepositoryInterface;
use Reith\ToyRobot\Messaging\Query\RobotReport;
use Reith\ToyRobot\Messaging\Annotation\Subscribe;

class RobotReporter
{
    private $robotRepository;

    private $logger;

    /**
     * @param RobotRepositoryInterface $robotRepository
     * @param LoggerInterface          $logger
     */
    public function __construct(
        RobotRepositoryInterface $robotRepository,
        LoggerInterface $logger
    ) {
        $this->robotRepository = $robotRepository;
        $this->logger = $logger;
    }

    /**
     * @Subscribe
     *
     * @param RobotReport $query
     * @return string
     */
    public function handleReport(RobotReport $query): string
    {
        $robot = $this->robotRepository->load();

        if (!($robot instanceof Robot)) {
            throw new \RuntimeException(
                'Robot cannot be found'
            );
        }

        return $robot->getReportAsString();
    }
}
