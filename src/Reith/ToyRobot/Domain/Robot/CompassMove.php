<?php

/**
 * (c) 2018 Douglas Reith
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types = 1);

namespace Reith\ToyRobot\Domain\Robot;

/**
 * Model movement as the application of a Vector (place)
 * in a 2 dimensional space.
 */
class CompassMove
{
    public static function northward(): Place
    {
        return Place::create([0, 1]);
    }

    public static function eastward(): Place
    {
        return Place::create([1, 0]);
    }

    public static function southward(): Place
    {
        return Place::create([0, -1]);
    }

    public static function westward(): Place
    {
        return Place::create([-1, 0]);
    }
}
