<?php

/**
 * (c) 2018 Douglas Reith
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types = 1);

namespace Reith\ToyRobot\Domain\Space;

use Reith\ToyRobot\Domain\Space\Exception\PlaceDimensionsDoNotMatchSpaceException;
use Reith\ToyRobot\Domain\Robot\Robot;
use Reith\ToyRobot\Domain\Robot\Place;

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
     * @return Place
     */
    protected function defaultPlacement(): Place
    {
        // Create a place with position [0,0,..n]
        return Place::create(array_fill(0, $this->dimensions, 0));
    }

    /**
     * @param ?Place $place
     * @return Robot
     */
    public function placeRobot(?Place $place = null): Robot
    {
        $place = $place ?: $this->defaultPlacement();

        if ($this->isAGoodPlace($place)) {
            // A robot is in a space with a place
            return Robot::create($this, $place);
        }
    }

    /**
     * @param Place $place
     * @return bool
     * @throws \Reith\ToyRobot\Domain\Space\Exception\BoundaryTestException
     */
    public function isAGoodPlace(Place $place): bool
    {
        $condition = $this->boundaryCondition;

        $place->map(function ($value) use ($condition) {
            $condition->test($value);
        });

        return true;
    }

    /**
     * @param Place      $from
     * @param Place|null $to   If not supplied, origin is $from
     * @throws PlaceDimensionsDoNotMatchSpaceException
     * @throws \Reith\ToyRobot\Domain\Space\Exception\BoundaryTestException
     */
    public function move(Place $from, ?Place $to = null): Place
    {
        if (!$to) {
            $to = $from;
            // Origin
            $from = $this->defaultPlacement();
        }

        // Ensure the movement is of the same dimension as the
        // space
        $this->validateDimensionality($from, $to);

        // Add the vectors to get the new position
        $final = Place::createFromVector($from->add($to));

        if ($this->isAGoodPlace($final)) {
            return $final;
        }
    }

    /**
     * For all the places passed, ensure they are of the same
     * dimensionality.
     *
     * @param Place $place ... Places to check
     * @throws PlaceDimensionsDoNotMatchSpaceException
     */
    private function validateDimensionality(Place ...$places): void
    {
        $spaceDimensions = $this->dimensions;

        array_walk(
            $places,
            function (Place $place) use ($spaceDimensions) {
                $errorMsg = sprintf(
                    'Place has [%d] dimensions but space has [%d] dimensions',
                    $place->getN(),
                    $spaceDimensions
                );

                if ($place->getN() !== $spaceDimensions) {
                    throw new PlaceDimensionsDoNotMatchSpaceException(
                        $errorMsg
                    );
                }
            }
        );
    }
}
