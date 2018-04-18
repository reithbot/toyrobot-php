<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Domain\Space;

use MathPHP\LinearAlgebra\Vector;
use Reith\ToyRobot\Domain\Space\Exception\PlaceDimensionsDoNotMatchSpaceException;
use Reith\ToyRobot\Domain\Robot\Robot;

abstract class AbstractSymmetricSpace implements SpaceInterface
{
    private $dimensions;

    private $boundarySize;

    private $boundaryCondition;

    /**
     * @param int $dimensions
     * @param int $boundarySize
     */
    protected function __construct(int $dimensions, int $boundarySize)
    {
        $this->dimensions = $dimensions;
        $this->boundarySize = $boundarySize;
        $this->boundaryCondition = BoundaryCondition::create($boundarySize);
    }

    /**
     * @return Vector
     */
    protected function defaultPosition(): Vector
    {
        // Create a default position [0,0,..n]
        return new Vector(array_fill(0, $this->dimensions, 0));
    }

    /**
     * @param Vector|null $position
     * @param string|null $startingDirection
     *
     * @return Robot
     */
    public function placeRobot(?Vector $position = null, ?string $startingDirection = null): Robot
    {
        $position = $position ?: $this->defaultPosition();

        if ($this->isAGoodPosition($position)) {
            // A robot is in a space with a place
            return Robot::create($this, $position, $startingDirection);
        }
    }

    /**
     * @param Vector $place
     *
     * @return bool
     *
     * @throws \Reith\ToyRobot\Domain\Space\Exception\BoundaryTestException
     */
    public function isAGoodPosition(Vector $position): bool
    {
        $condition = $this->boundaryCondition;

        // Get raw array
        $coords = $position->getVector();

        array_walk(
            $coords,
            function (int $value) use ($condition) {
                $condition->test($value);
            }
        );

        return true;
    }

    /**
     * @param Vector      $from
     * @param Vector|null $to   If not supplied, origin is $from
     *
     * @throws PlaceDimensionsDoNotMatchSpaceException
     * @throws \Reith\ToyRobot\Domain\Space\Exception\BoundaryTestException
     */
    public function move(Vector $from, ?Vector $to = null): Vector
    {
        if (!$to) {
            $to = $from;
            // Origin
            $from = $this->defaultPosition();
        }

        // Ensure the movement is of the same dimension as the
        // space
        $this->validateDimensionality($from, $to);

        // Add the vectors to get the new position
        $final = $from->add($to);

        if ($this->isAGoodPosition($final)) {
            return $final;
        }
    }

    /**
     * For all the places passed, ensure they are of the same
     * dimensionality.
     *
     * @param Vector $vectors ... Vectors to check
     *
     * @throws PlaceDimensionsDoNotMatchSpaceException
     */
    private function validateDimensionality(Vector ...$vectors): void
    {
        $spaceDimensions = $this->dimensions;

        array_walk(
            $vectors,
            function (Vector $vector) use ($spaceDimensions) {
                // Ensure the dimensions are the same
                if ($vector->getN() !== $spaceDimensions) {
                    $errorMsg = sprintf(
                        'There is a position that has [%d] dimensions but space has [%d] dimensions',
                        $vector->getN(),
                        $spaceDimensions
                    );

                    throw new PlaceDimensionsDoNotMatchSpaceException(
                        $errorMsg
                    );
                }
            }
        );
    }
}
