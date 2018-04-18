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

final class RobotReporter
{
    private $repository;

    private $logger;

    /**
     * @param RobotRepositoryInterface $repository
     * @param LoggerInterface          $logger
     */
    public function __construct(
        RobotRepositoryInterface $repository,
        LoggerInterface $logger
    ) {
        $this->repository = $repository;
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
        $robot = $this->repository->load();

        if (!($robot instanceof Robot)) {
            throw new \RuntimeException(
                'Robot cannot be found. Please PLACE the robot first.'
            );
        }

        return $robot->getReportAsString();
    }
}
