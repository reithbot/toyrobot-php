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
use Reith\ToyRobot\Domain\Robot\Place;
use Reith\ToyRobot\Domain\Space\SpaceInterface;
use Reith\ToyRobot\Messaging\Command\PlaceRobot;
use Reith\ToyRobot\Messaging\Annotation\Subscribe;

class RobotPlacer
{
    private $spaceInterface;

    private $robotRepository;

    private $logger;

    /**
     * @param SpaceInterface           $space
     * @param RobotRepositoryInterface $robotRepository
     * @param LoggerInterface          $logger
     */
    public function __construct(
        SpaceInterface $space,
        RobotRepositoryInterface $robotRepository,
        LoggerInterface $logger
    ) {
        $this->space = $space;
        $this->robotRepository = $robotRepository;
        $this->logger = $logger;
    }

    /**
     * @Subscribe
     *
     * @param PlaceRobot $command
     */
    public function handlePlaceRobot(PlaceRobot $command): void
    {
        $robot = $this->space->placeRobot(
            new Vector($command->getCoordinates())
        );

        $this->robotRepository->save($robot);

        $this->logger->info('Robot placed');
    }
}
