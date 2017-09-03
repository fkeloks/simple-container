<?php

namespace SimpleContainerTests\Fake;

/**
 * Class FakeClassC
 * @package SimpleContainerTests\Fake
 */
class FakeClassC
{

    /**
     * @var \DateTime
     */
    private $dateA;

    /**
     * FakeClassC constructor.
     * @param $dateA
     */
    public function __construct($dateA) {
        $this->dateA = $dateA;
    }

    /**
     * @return \DateTime
     */
    public function getDateA() {
        return $this->dateA;
    }

}