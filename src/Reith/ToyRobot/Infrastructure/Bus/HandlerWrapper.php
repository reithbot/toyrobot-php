<?php

/**
 * (c) 2018 Douglas Reith.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Reith\ToyRobot\Infrastructure\Bus;

use Assert\Assertion;
use Doctrine\Common\Annotations\AnnotationReader;
use Reith\ToyRobot\Messaging\Annotation\Subscribe;

class HandlerWrapper
{
    private $handler;

    private $callables;

    /**
     * Part of this inspired by `AnnotatedMessageHandlerDescriptor`
     * in https://github.com/szjani/predaddy.
     *
     * @param mixed $handler
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __construct($handler)
    {
        Assertion::isObject($handler, 'Expected an object for a handler');

        $reflection = new \ReflectionClass($handler);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        Assertion::greaterThan(
            count($methods),
            0,
            sprintf('No methods found on handler [%s]', get_class($handler))
        );

        $reader = new AnnotationReader();

        foreach ($methods as $method) {
            $methodAnnotation = $reader->getMethodAnnotation($method, Subscribe::class);

            if (!$methodAnnotation) {
                continue;
            }

            $params = $method->getParameters();
            Assertion::eq(
                count($params),
                1,
                sprintf(
                    'Expect only one argument for a handler method [%s::%s()]',
                    get_class($handler),
                    $method->getName()
                )
            );

            $param = $params[0];

            $paramClass = $param->getClass();

            // Create an index of message class mapped to a callable:
            // [$obj, 'methodName']
            $this->callables[$paramClass->getName()] = [$handler, $method->getName()];
        }

        Assertion::greaterThan(
            count($this->callables),
            0,
            sprintf('No handler methods found on [%s]', get_class($handler))
        );
    }

    /**
     * @param mixed $message
     *
     * @return callable
     */
    public function getCallable($message): ?callable
    {
        Assertion::isObject($message, 'Expected an object for a message');

        if (isset($this->callables[get_class($message)])) {
            return $this->callables[get_class($message)];
        }

        return null;
    }
}
