<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\CommandHandler;

use MathPHP\LinearAlgebra\Vector;
use Psr\Log\LoggerInterface;
use Reith\ToyRobot\Domain\Robot\RobotRepositoryInterface;
use Reith\ToyRobot\Domain\Robot\Robot;

use Reith\ToyRobot\Messaging\Annotation\Subscribe;
use Reith\ToyRobot\Messaging\Command\TurnLeft;
use Reith\ToyRobot\Messaging\Command\TurnRight;
use Reith\ToyRobot\Messaging\Command\MoveForward;

final class RobotMover
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
     * @param TurnLeft $command
     */
    public function handleTurningLeft(TurnLeft $command): void
    {
        $robot = $this->getRobot();

        $robot->left();

        $this->repository->save($robot);

        $this->logger->info('Robot turned left');
    }

    /**
     * @Subscribe
     *
     * @param TurnRight $command
     */
    public function handleTurningRight(TurnRight $command): void
    {
        $robot = $this->getRobot();

        $robot->right();

        $this->repository->save($robot);

        $this->logger->info('Robot turned right');
    }

    /**
     * @Subscribe
     *
     * @param MoveForward $command
     */
    public function handleMoveForward(MoveForward $command): void
    {
        $robot = $this->getRobot();

        $robot->move();

        $this->repository->save($robot);

        $this->logger->info('Robot moved forward');
    }

    /**
     * @return Robot
     */
    private function getRobot(): Robot
    {
        $robot = $this->repository->load();

        if (!($robot instanceof Robot)) {
            throw new \RuntimeException(
                'Robot cannot be found. Please PLACE the robot first.'
            );
        }

        return $robot;
    }
}
