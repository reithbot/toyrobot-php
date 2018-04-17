<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Infrastructure\Persistence;

use Reith\ToyRobot\Domain\Robot\Robot;

class FileRobotStore implements RobotStoreInterface
{
    private const FILE_VERSION = '001';

    private static $store;

    private $file;

    /**
     * __construct.
     *
     * @param \SplFileObject $file
     */
    private function __construct(\SplFileObject $file)
    {
        $this->file = $file;
    }

    public function __destruct()
    {
        $this->file = null;
    }

    /**
     * getStore.
     *
     * @param string $basePath
     *
     * @return RobotStoreInterface
     */
    public static function getStore(string $basePath): RobotStoreInterface
    {
        // Ensure the same store is used everywhere
        if (!self::$store) {
            self::$store = self::createStore($basePath);
        }

        return self::$store;
    }

    /**
     * createStore.
     *
     * @param string $basePath
     *
     * @return RobotStoreInterface
     */
    private static function createStore(string $basePath): RobotStoreInterface
    {
        $baseDir = $basePath . DIRECTORY_SEPARATOR . 'robotstore';

        if (false === file_exists($baseDir)) {
            mkdir($baseDir, 0644, true);
        }

        $fileName = $baseDir . DIRECTORY_SEPARATOR . self::FILE_VERSION . '-robot.txt';

        if (false === file_exists($fileName)) {
            touch($fileName);
        }

        // w+ - for reading and writing
        return new static(new \SplFileObject($fileName), 'w+');
    }

    /**
     * @return Robot|null
     */
    public function getRobot(): ?Robot
    {
        if (!$this->file->getSize()) {
            return null;
        }

        $this->file->rewind();

        $contents = $this->file->fread($this->file->getSize());

        return unserialize($contents);
    }

    /**
     * @param Robot $robot
     */
    public function saveRobot(Robot $robot): void
    {
        // Empty the file
        $this->file->ftruncate(0);

        // Save the robot
        $this->file->fwrite(serialize($robot));
    }
}
