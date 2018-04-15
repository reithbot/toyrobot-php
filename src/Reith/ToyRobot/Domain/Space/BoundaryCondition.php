<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Domain\Space;

use Assert\Assert;
use Reith\ToyRobot\Domain\Space\Exception\BoundaryTestException;

class BoundaryCondition
{
    private $bounds;

    /**
     * @param int $bounds
     */
    private function __construct(int $bounds)
    {
        $this->bounds = $bounds;
    }

    /**
     * @param int $bounds
     *
     * @throws \Assert\AssertionFailedException
     */
    public static function create(int $bounds): BoundaryCondition
    {
        // Boundary conditions must be greater than zero
        Assert::that($bounds)->greaterThan(0);

        return new self($bounds);
    }

    /**
     * @param int $value
     *
     * @return bool
     *
     * @throws BoundaryTestException
     */
    public function test(int $value): bool
    {
        $boundary = 1 / $this->bounds;

        $positionDenominator = $this->bounds - $value;

        // Div by zero
        if (0 === $positionDenominator) {
            $this->throwBoundaryTestException($value);
        }

        $position = 1 / $positionDenominator;

        if ($position < $boundary) {
            $this->throwBoundaryTestException($value);
        }

        return true;
    }

    /**
     * @param int $value
     *
     * @throws BoundaryTestException
     */
    private function throwBoundaryTestException(int $value): void
    {
        $msg = sprintf('[%d] is outside the bounds of [0..%d]', $value, $this->bounds - 1);

        throw new BoundaryTestException($msg);
    }
}
