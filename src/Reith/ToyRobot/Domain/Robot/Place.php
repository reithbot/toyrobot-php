<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Domain\Robot;

use Assert\Assert;
use MathPHP\LinearAlgebra\Vector;

/**
 * We can treat a placement as a Vector with origin at 0,0,n.
 *
 * @see Vector
 */
class Place extends Vector
{
    /**
     * @param array $coordinates
     *
     * @return Place
     *
     * @throws Assert\AssertionFailedException
     */
    public static function create(array $coordinates): Place
    {
        Assert::that($coordinates)->notEmpty();
        Assert::thatAll($coordinates)->integer();

        return new static($coordinates);
    }

    /**
     * @param callable $fn
     *
     * @return array
     */
    public function map(callable $fn): array
    {
        return array_map($fn, $this->getVector());
    }

    /**
     * @param Vector $v
     *
     * @return Place
     */
    public static function createFromVector(Vector $v): Place
    {
        return static::create($v->getVector());
    }
}
