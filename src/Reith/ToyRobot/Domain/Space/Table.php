<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Domain\Space;

class Table extends AbstractSymmetricSpace
{
    /**
     * @param int $boundarySize
     */
    public static function create(int $boundarySize): Table
    {
        return new static(
            2, // 2 dimensions x,y
            $boundarySize
        );
    }
}
