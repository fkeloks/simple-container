<?php

namespace SimpleContainer;

use Psr\Container\ContainerInterface;

/**
 * Class Container
 * Container must be instanced from ContainerBuilder
 *
 * @package SimpleContainer
 */
class Container implements ContainerInterface
{

    /**
     * @var array Current Configuration
     */
    private $configuration = [];

    /**
     * Container constructor.
     * Container must be instanced from ContainerBuilder
     *
     * @param array $configuration
     */
    public function __construct(array $configuration) {
        $this->configuration = $configuration;
    }

    /**
     * Return class from key name
     *
     * @param string $className
     *
     * @return mixed|object
     */
    public function get($className) {
        $classId = $this->getClassId($className);
        $parameters = $this->getParams($className);

        if (CacheContainer::has($classId)) {
            return CacheContainer::get($classId);
        }

        if (is_array($parameters)) {
            $reflector = new \ReflectionClass($classId);

            return $reflector->newInstanceArgs($parameters);
        }

        $class = (!is_null($parameters)) ? new $classId($parameters) : new $classId();

        return CacheContainer::set($classId, $class);
    }

    /**
     * Return new instance of key name
     *
     * @param string $className
     *
     * @return mixed|object
     */
    public function make(string $className) {
        $classId = $this->getClassId($className);
        $parameters = $this->getParams($className);

        if (is_array($parameters)) {
            $reflector = new \ReflectionClass($classId);

            return $reflector->newInstanceArgs($parameters);
        }

        $class = (!is_null($parameters)) ? new $classId($parameters) : new $classId();

        return CacheContainer::set($classId, $class);
    }

    /**
     * Check if cache contains key
     *
     * @param string $className
     *
     * @return bool
     */
    public function has($className): bool {
        return CacheContainer::has($className);
    }

    /**
     * Return classId from key name
     *
     * @param string $className
     *
     * @return mixed|string ClassId
     */
    private function getClassId(string $className) {
        $classId = (array_key_exists($className, $this->configuration)) ? $this->configuration[$className] : $className;

        return (is_array($classId)) ? $classId['class'] : $classId;
    }

    /**
     * Return parameters from a key
     *
     * @param string $className
     *
     * @return mixed|null
     * @throws ContainerNotFoundException
     */
    private function getParams(string $className) {
        if (isset($this->configuration[$className])) {
            if (is_array($this->configuration[$className])) {
                return $this->configuration[$className]['params'];
            } else {
                return $this->configuration[$className];
            }
        } elseif (class_exists($className)) {
            return null;
        } else {
            throw new ContainerNotFoundException('The class "' . $className . '" can not be found');
        }
    }

}