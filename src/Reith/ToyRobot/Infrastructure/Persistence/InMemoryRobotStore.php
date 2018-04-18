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

/**
 * Class: InMemoryRobotStore
 *
 * This store is used in testing scenarios. Though if we implement an
 * interactive mode, then we could use this instead of FileRobotStore.
 *
 * @see RobotStoreInterface
 */
class InMemoryRobotStore implements RobotStoreInterface
{
    private static $store;

    private $logger;

    private $robot;

    /**
     * __construct.
     *
     * @param LoggerInterface|null $logger
     */
    private function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param LoggerInterface|null $logger
     *
     * @return RobotStoreInterface
     */
    public static function getStore(?LoggerInterface $logger = null): RobotStoreInterface
    {
        // Ensure the same store is used everywhere
        if (!self::$store) {
            self::$store = self::createStore($logger);
        }

        return self::$store;
    }

    /**
     * @param LoggerInterface|null $logger
     *
     * @return RobotStoreInterface
     */
    private static function createStore(?LoggerInterface $logger): RobotStoreInterface
    {
        return new static($logger);
    }

    /**
     * @return Robot|null
     */
    public function getRobot(): ?Robot
    {
        return $this->robot;
    }

    /**
     * @param Robot $robot
     */
    public function saveRobot(Robot $robot): void
    {
        $this->robot = $robot;
        $this->log('Saved robot');
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
