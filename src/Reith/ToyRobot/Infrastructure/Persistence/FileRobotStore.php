<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Infrastructure\Persistence;

use Psr\Log\LoggerInterface;
use Reith\ToyRobot\Domain\Robot\Robot;

class FileRobotStore implements RobotStoreInterface
{
    private const FILE_VERSION = '001';

    private static $store;

    private $file;

    private $logger;

    /**
     * __construct.
     *
     * @param \SplFileObject $file
     * @param LoggerInterface|null $logger
     */
    private function __construct(\SplFileObject $file, ?LoggerInterface $logger = null)
    {
        $this->file = $file;
        $this->logger = $logger;
    }

    public function __destruct()
    {
        $this->file = null;
    }

    /**
     * @param string               $basePath
     * @param LoggerInterface|null $logger
     *
     * @return RobotStoreInterface
     */
    public static function getStore(string $basePath, ?LoggerInterface $logger = null): RobotStoreInterface
    {
        // Ensure the same store is used everywhere
        if (!self::$store) {
            self::$store = self::createStore($basePath, $logger);
        }

        return self::$store;
    }

    /**
     * @param string               $basePath
     * @param LoggerInterface|null $logger
     *
     * @return RobotStoreInterface
     */
    private static function createStore(string $basePath, ?LoggerInterface $logger): RobotStoreInterface
    {
        $baseDir = $basePath . DIRECTORY_SEPARATOR . 'robotstore';

        if (false === file_exists($baseDir)) {
            mkdir($baseDir, 0644, true);
        }

        $fileName = $baseDir . DIRECTORY_SEPARATOR . self::FILE_VERSION . '-robot.txt';

        if (false === file_exists($fileName)) {
            touch($fileName);
        }

        // c+ - for reading and writing but do not truncate
        return new static(new \SplFileObject($fileName, 'c+'), $logger);
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

        $this->file->rewind();

        // Save the robot
        $bytes = $this->file->fwrite(serialize($robot));
        $this->file->fflush();

        $this->log(sprintf('Wrote [%d] robot bytes to [%s]', $bytes, $this->file->getRealPath()));
    }

    /**
     * @param string $msg
     */
    private function log(string $msg): void
    {
        if (!$this->logger) {
            return;
        }

        $this->logger->info($msg);
    }
}
