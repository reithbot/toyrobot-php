<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Domain\Robot;

use MathPHP\LinearAlgebra\Vector;
use Assert\Assert;
use Assert\Assertion;
use Reith\ToyRobot\Domain\Space\SpaceInterface;
use Reith\ToyRobot\Domain\Robot\Exception\NotPlacedInSpaceException;

class Robot
{
    /**
     * Purchased by Rupert, then tanked :).
     *
     * @var SpaceInterface
     */
    private $mySpace;

    private $position;

    private $facingDirection;

    private function __construct(
        SpaceInterface $space,
        Vector $position,
        Direction $facingDirection
    ) {
        $this->mySpace = $space;
        $this->position = $position;
        $this->facingDirection = $facingDirection;
    }

    /**
     * @param SpaceInterface $space
     * @param Vector         $position
     * @param string|null    $facingDirection
     *
     * @return Robot
     *
     * @throws Assert\AssertionFailedException
     */
    public static function create(
        SpaceInterface $space,
        Vector $position,
        ?string $facingDirection = 'E'
    ): Robot {
        return new static(
            $space,
            $position,
            new Direction($facingDirection)
        );
    }

    public function move(): Robot
    {
        $this->validateCanMove();

        $this->position = $this->mySpace->move(
            $this->position,
            $this->facingDirection->getDirectionAsVector()
        );

        return $this;
    }

    public function left(): Robot
    {
        $this->facingDirection->rotateLeft();

        return $this;
    }

    public function right(): Robot
    {
        $this->facingDirection->rotateRight();

        return $this;
    }

    public function moveNorthward(): Robot
    {
        $this->validateCanMove();

        $this->position = $this->mySpace->move(
            $this->position,
            $this->facingDirection->northward()
        );

        return $this;
    }

    public function moveEastward(): Robot
    {
        $this->validateCanMove();

        $this->position = $this->mySpace->move(
            $this->position,
            $this->facingDirection->eastward()
        );

        return $this;
    }

    public function moveSouthward(): Robot
    {
        $this->validateCanMove();

        $this->position = $this->mySpace->move(
            $this->position,
            $this->facingDirection->southward()
        );

        return $this;
    }

    public function moveWestward(): Robot
    {
        $this->validateCanMove();

        $this->position = $this->mySpace->move(
            $this->position,
            $this->facingDirection->westward()
        );

        return $this;
    }

    public function getPosition(): Vector
    {
        return $this->position;
    }

    /**
     * validateCanMove.
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
