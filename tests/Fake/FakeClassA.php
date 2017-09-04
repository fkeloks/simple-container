<?php

namespace SimpleContainerTests\Fake;

/**
 * Class FakeClassA
 * @package SimpleContainerTests\Fake
 */
class FakeClassA
{

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * FakeClassA constructor.
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