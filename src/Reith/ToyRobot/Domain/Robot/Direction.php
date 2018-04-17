<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Domain\Robot;

use Assert\Assertion;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Vector;

/**
 * Manage robot direction.
 *
 * Matrix multiplication gives us the new direction.
 */
class Direction
{
    // Counter clockwise
    private const ROTATE_LEFT_MATRIX = [
        [0, -1],
        [1, 0]
    ];

    // Clockwise
    private const ROTATE_RIGHT_MATRIX = [
        [0, 1],
        [-1, 0]
    ];

    private const CHAR_VECTOR_MAPPING = [
        'N' => [0, 1],
        'E' => [1, 0],
        'S' => [0, -1],
        'W' => [-1, 0],
    ];

    private $leftTransform;

    private $rightTransform;

    private $direction;

    /**
     * @param string|null $startingDirection
     */
    public function __construct(?string $startingDirection = 'E')
    {
        // Normalise
        $startingDirection = strtoupper(trim($startingDirection));

        Assertion::choice($startingDirection, ['N', 'S', 'E', 'W']);

        $this->setDirectionFromString($startingDirection);

        $this->leftTransform = MatrixFactory::create(self::ROTATE_LEFT_MATRIX);
        $this->rightTransform = MatrixFactory::create(self::ROTATE_RIGHT_MATRIX);
    }

    /**
     * @param string $direction
     */
    private function setDirectionFromString(string $direction): void
    {
        $this->direction = new Vector(
            self::CHAR_VECTOR_MAPPING[$direction]
        );
    }

    /**
     * @return Direction
     */
    public function rotateLeft(): Direction
    {
        $this->direction = $this->leftTransform->vectorMultiply(
            $this->direction
        );

        return $this;
    }

    /**
     * @return Direction
     */
    public function rotateRight()
    {
        $this->direction = $this->rightTransform->vectorMultiply(
            $this->direction
        );

        return $this;
    }

    /**
     * @return Vector
     */
    public function getDirectionAsVector(): Vector
    {
        return $this->direction;
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public function getDirectionAsString(): string
    {
        foreach (self::CHAR_VECTOR_MAPPING as $char => $coords) {
            // Array equality === same key, value pairs in the same
            // order (no type casting)
            if ($coords === $this->direction->getVector()) {
                return $char;
            }
        }

        throw new \LogicException(
            'Unable to find the direction char from the vector'
        );
    }
}
