<?php

namespace SimpleContainer;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Class ContainerNotFoundException
 * @package SimpleContainer
 */
class ContainerNotFoundException extends \Exception implements NotFoundExceptionInterface
{

}