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

class Robot implements \Serializable
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

        $facingDirection = $facingDirection ?: 'E';

        return new static(
            $space,
            $position,
            new Direction($facingDirection)
        );
    }

    /**
     * @return Robot
     */
    public function move(): Robot
    {
        $this->validateCanMove();

        $this->position = $this->mySpace->move(
            $this->position,
            $this->facingDirection->getDirectionAsVector()
        );

        return $this;
    }

    /**
     * @return Robot
     */
    public function left(): Robot
    {
        $this->facingDirection->rotateLeft();

        return $this;
    }

    /**
     * @return Robot
     */
    public function right(): Robot
    {
        $this->facingDirection->rotateRight();

        return $this;
    }

    /**
     * @return Robot
     */
    public function moveNorthward(): Robot
    {
        $this->validateCanMove();

        $this->position = $this->mySpace->move(
            $this->position,
            $this->facingDirection->northward()
        );

        return $this;
    }

    /**
     * @return Robot
     */
    public function moveEastward(): Robot
    {
        $this->validateCanMove();

        $this->position = $this->mySpace->move(
            $this->position,
            $this->facingDirection->eastward()
        );

        return $this;
    }

    /**
     * @return Robot
     */
    public function moveSouthward(): Robot
    {
        $this->validateCanMove();

        $this->position = $this->mySpace->move(
            $this->position,
            $this->facingDirection->southward()
        );

        return $this;
    }

    /**
     * @return Robot
     */
    public function moveWestward(): Robot
    {
        $this->validateCanMove();

        $this->position = $this->mySpace->move(
            $this->position,
            $this->facingDirection->westward()
        );

        return $this;
    }

    /**
     * @return Vector
     */
    public function getPosition(): Vector
    {
        return $this->position;
    }

    /**
     * @return string In the form 'X,Y,F'
     */
    public function getReportAsString(): string
    {
        $positionAsString = implode(',', $this->position->getVector());

        return $positionAsString . ',' . $this->getFacingDirectionAsString();
    }

    /**
     * Simplify the serialization of the Robot to
     * scalars
     *
     * @return string
     */
    public function serialize(): string
    {
        return serialize([
            $this->mySpace,
            $this->position->getVector(),
            $this->getFacingDirectionAsString()
        ]);
    }

    /**
     * Re-hydrate the Robot
     *
     * @param string $data
     */
    public function unserialize($data)
    {
        [$this->mySpace, $positionArr, $facingString] = unserialize($data);

        $this->position = new Vector($positionArr);
        $this->facingDirection = new Direction($facingString);
    }

    /**
     * @return string
     */
    private function getFacingDirectionAsString(): string
    {
        return $this->facingDirection->getDirectionAsString();
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
