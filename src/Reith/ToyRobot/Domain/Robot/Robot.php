<?php

/**
 * (c) 2018 Douglas Reith
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types = 1);

namespace Reith\ToyRobot\Domain\Robot;

use Assert\Assert;
use Reith\ToyRobot\Domain\Space\SpaceInterface;
use Reith\ToyRobot\Domain\Robot\Exception\NotPlacedInSpaceException;

class Robot
{
    /**
     * Purchased by Rupert, then tanked :)
     *
     * @var SpaceInterface
     */
    private $mySpace;

    private $placement;

    private function __construct(SpaceInterface $space, Place $placement)
    {
        $this->mySpace = $space;
        $this->placement = $placement;
    }

    /**
     * @param SpaceInterface $space
     * @param Place          $placement
     * @return Robot
     * @throws Assert\AssertionFailedException
     */
    public function create(SpaceInterface $space, Place $placement): Robot
    {
        return new static($space, $placement);
    }

    public function moveNorthward(): Robot
    {
        $this->validateCanMove();

        $this->placement = $this->mySpace->move(
            $this->placement,
            CompassMove::northward()
        );

        return $this;
    }

    public function moveEastward(): Robot
    {
        $this->validateCanMove();

        $this->placement = $this->mySpace->move(
            $this->placement,
            CompassMove::eastward()
        );

        return $this;
    }

    public function moveSouthward(): Robot
    {
        $this->validateCanMove();

        $this->placement = $this->mySpace->move(
            $this->placement,
            CompassMove::southward()
        );

        return $this;
    }

    public function moveWestward(): Robot
    {
        $this->validateCanMove();

        $this->placement = $this->mySpace->move(
            $this->placement,
            CompassMove::westward()
        );

        return $this;
    }

    public function getPlacement(): Place
    {
        return $this->placement;
    }

    /**
     * validateCanMove
     *
     * The robot requires a space during construction, however,
     * this is extra precautionary in case the space is removed
     *
     * @throws NotPlacedInSpaceException
     */
    private function validateCanMove(): void
    {
        if (!$this->mySpace) {
            throw new NotPlacedInSpaceException(
                'I cannot move until placed in space'
            );
        }
    }
}
