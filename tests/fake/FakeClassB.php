<?php

namespace SimpleContainerTests\Fake;

/**
 * Class FakeClassB
 * @package SimpleContainerTests\Fake
 */
class FakeClassB
{

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * FakeClassB constructor.
     */
    public function __construct() {
        $this->date = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

}