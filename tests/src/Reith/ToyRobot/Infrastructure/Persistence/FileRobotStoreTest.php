<?php
/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Infrastructure\Persistence;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use Reith\ToyRobot\Domain\Space\Table;
use Reith\ToyRobot\Domain\Robot\Robot;

class FileRobotStoreTest extends TestCase
{
    /**
     * @test
     */
    public static function canCreateAStore()
    {
        $basePath = vfsStream::setup('basePath');

        self::assertFalse($basePath->hasChild('robotstore'));

        $store = FileRobotStore::getStore(vfsStream::url('basePath'));

        self::assertTrue($basePath->hasChild('robotstore'));

        $version = '001';
        $fileName = $version . '-robot.txt';

        $robotStorePath = $basePath->getChild('robotstore');
        self::assertTrue($robotStorePath->hasChild($fileName));

        self::assertInstanceOf(FileRobotStore::class, $store);
    }

    /**
     * @test
     */
    public static function canPersistAndRetrieveARobot()
    {
        self::markTestSkipped('Issue with the vfsStream mock filesystem and SplFileObject');

        $basePath = vfsStream::setup('basePath');
        $store = FileRobotStore::getStore(vfsStream::url('basePath'));

        $robot = Table::create(5)->placeRobot();

        $store->saveRobot($robot);

        $persistedRobot = $store->getRobot();

        self::assertInstanceOf(Robot::class, $persistedRobot);
    }
}
