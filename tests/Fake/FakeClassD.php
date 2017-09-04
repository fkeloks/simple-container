<?php

namespace SimpleContainerTests\Fake;

/**
 * Class FakeClassD
 * @package SimpleContainerTests\Fake
 */
class FakeClassD
{

    /**
     * @var \DateTime
     */
    private $dateA;

    /**
     * @var \DateTime
     */
    private $dateB;

    /**
     * FakeClassD constructor.
     * @param $dateA
     * @param $dateB
     */
    public function __construct($dateA, $dateB = null) {
        $this->dateA = $dateA;
        $this->dateB = $dateB;
    }

    /**
     * @return \DateTime
     */
    public function getDateA() {
        return $this->dateA;
    }

    /**
     * @return \DateTime
     */
    public function getDateB() {
        return $this->dateB;
    }

}