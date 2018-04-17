<?php
/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationRegistry;

// Set up the handler Subscribe annotation
AnnotationRegistry::registerFile(__DIR__ . '/../src/Reith/ToyRobot/Messaging/Annotation/Subscribe.php');
