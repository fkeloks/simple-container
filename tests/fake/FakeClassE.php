<?php

namespace SimpleContainerTests\Fake;

/**
 * Class FakeClassE
 * @package SimpleContainerTests\Fake
 */
class FakeClassE
{

    /**
     * @var array
     */
    private $values;

    /**
     * FakeClassE constructor.
     * @param array $values
     */
    public function __construct(array $values = []) {
        $this->values = $values;
    }

    /**
     * @return array
     */
    public function getValues(): array {
        return $this->values;
    }

}