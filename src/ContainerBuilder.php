<?php

namespace SimpleContainer;

/**
 * Class ContainerBuilder
 * @package SimpleContainer
 */
class ContainerBuilder
{

    /**
     * @var Container
     */
    private static $instance;

    /**
     * Validate configuration
     *
     * @param array $configuration
     *
     * @return void
     * @throws ContainerException
     */
    public static function validateConfig(array $configuration) {
        foreach ($configuration as $item => $subItem) {
            if (is_string($subItem)) {
                if (!class_exists($subItem)) {
                    throw new ContainerException('Container configuration is invalid: class "' . $subItem . '" does not exist.');
                }
            } elseif (is_array($subItem)) {
                if (array_key_exists('class', $subItem) && array_key_exists('params', $subItem)) {
                    if (!class_exists($subItem['class'])) {
                        throw new ContainerException('Container configuration is invalid: class "' . $subItem['class'] . '" does not exist.');
                    }
                } else {
                    throw new ContainerException('The configuration of the container is invalid: The parameters \'class\' and \'params\' must be present for the key "' . $item . '".');
                }
            }
        }
    }

    /**
     * Returns the container if it was built
     *
     * @return Container
     * @throws ContainerException
     */
    public static function getContainer(): Container {
        if (self::$instance instanceof Container) {
            return self::$instance;
        } else {
            throw new ContainerException('No instance of the Container was built.');
        }
    }

    /**
     * Build container with a configuration
     *
     * @param array $configuration
     * @return string Self
     */
    public static function build(array $configuration) {
        self::validateConfig($configuration);
        self::$instance = new Container($configuration);

        return self::class;
    }

    /**
     * Destroy the previously constructed container
     *
     * @return void
     */
    public static function destroy() {
        self::$instance = null;
    }

}