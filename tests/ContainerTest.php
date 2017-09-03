<?php

namespace SimpleContainerTests;

use PHPUnit\Framework\TestCase;
use SimpleContainer\{
    Container,
    ContainerBuilder,
    ContainerException,
    ContainerNotFoundException
};
use SimpleContainerTests\Fake\{
    FakeClassA,
    FakeClassB,
    FakeClassC,
    FakeClassD,
    FakeClassE,
    FakeClassF
};

/**
 * Class ContainerTest
 * @package SimpleContainerTests
 */
class ContainerTest extends TestCase
{

    /**
     * @var Container
     */
    private $container;

    public function setUp() {
        ContainerBuilder::build([
            FakeClassA::class => FakeClassA::class,
            FakeClassB::class => FakeClassA::class,
            'FakeB'           => FakeClassB::class,
            'FakeE'           => [
                'class'  => FakeClassE::class,
                'params' => [['A', 'B']]
            ]
        ]);
        $this->container = ContainerBuilder::getContainer();
    }

    public function testInstanceOfContainer() {
        $this->assertInstanceOf(Container::class, $this->container);

        $container = ContainerBuilder::build([])::getContainer();
        $this->assertInstanceOf(Container::class, $container);

        $container = ContainerBuilder::getContainer();
        $this->assertInstanceOf(Container::class, $container);
    }

    public function testGetContainerIfNotBuild() {
        ContainerBuilder::destroy();

        $this->expectException(ContainerException::class);
        $container = ContainerBuilder::getContainer();
    }

    public function testSimpleGet() {
        $classA = $this->container->get(FakeClassA::class);
        $this->assertInstanceOf(FakeClassA::class, $classA);

        $classB = $this->container->get(FakeClassB::class);
        $this->assertInstanceOf(FakeClassA::class, $classB);

        $classB = $this->container->get('FakeB');
        $this->assertInstanceOf(FakeClassB::class, $classB);

        $classF = $this->container->get(FakeClassF::class);
        $this->assertInstanceOf(FakeClassF::class, $classF);
    }

    public function testGetFromCache() {
        $classA = $this->container->get(FakeClassA::class);
        $dateOne = $classA->getDate();

        $classA = $this->container->get(FakeClassA::class);
        $dateTwo = $classA->getDate();

        $this->assertEquals($dateOne, $dateTwo);
    }

    public function testSimpleMake() {
        $classA = $this->container->make(FakeClassA::class);
        $this->assertInstanceOf(FakeClassA::class, $classA);

        $classB = $this->container->make(FakeClassB::class);
        $this->assertInstanceOf(FakeClassA::class, $classB);

        $classB = $this->container->make('FakeB');
        $this->assertInstanceOf(FakeClassB::class, $classB);
    }

    public function testMakeWithoutCache() {
        $classA = $this->container->make(FakeClassA::class);
        $dateOne = $classA->getDate();

        $classA = $this->container->make(FakeClassA::class);
        $dateTwo = $classA->getDate();

        $this->assertNotEquals($dateOne, $dateTwo);
    }

    public function testPeformances() {
        $container = $this->container;
        $times = [];

        for ($i = 0; $i < 100; $i++) {
            $timeStart = microtime(true);
            $container->get(FakeClassA::class);
            $times[] = round((microtime(true) - $timeStart) * 1000, 3);
        }

        $average = (array_sum($times) / count($times));
        $this->assertTrue($average <= 0.5);
    }

    public function testGetWithParamsIntoContructror() {
        $dateA = new \DateTime();
        $container = ContainerBuilder::build([
            'FakeC' => [
                'class'  => FakeClassC::class,
                'params' => $dateA
            ]
        ])::getContainer();

        $classC = $container->get('FakeC');
        $this->assertInstanceOf(FakeClassC::class, $classC);

        $dateB = $classC->getDateA();
        $this->assertEquals($dateA, $dateB);

        $classE = $this->container->get('FakeE');

        $values = $classE->getValues();
        $this->assertEquals($values, ['A', 'B']);
    }

    public function testGetWithMultiplesParamsIntoContructror() {
        $dateA = new \DateTime();
        $dateB = new \DateTime();
        $container = ContainerBuilder::build([
            'FakeD' => [
                'class'  => FakeClassD::class,
                'params' => [$dateA, $dateB]
            ]
        ])::getContainer();

        $classD = $container->get('FakeD');
        $this->assertInstanceOf(FakeClassD::class, $classD);

        $dateC = $classD->getDateA();
        $this->assertEquals($dateA, $dateC);

        $dateD = $classD->getDateB();
        $this->assertEquals($dateB, $dateD);

        $dateD = $classD->getDateB();
        $this->assertNotEquals($dateC, $dateD);
    }

    public function testInvalidConfiguration() {
        try {
            $container = ContainerBuilder::build([
                'FakeD' => InvalidClass::class,
            ])::getContainer();
        } catch (\Exception $e) {
            $this->assertInstanceOf(ContainerException::class, $e);
        }

        try {
            $container = ContainerBuilder::build([
                'FakeD' => [
                    'class' => InvalidClass::class
                ]
            ])::getContainer();
        } catch (\Exception $e) {
            $this->assertInstanceOf(ContainerException::class, $e);
        }

        try {
            $container = ContainerBuilder::build([
                'FakeD' => [
                    'params' => ['test']
                ]
            ])::getContainer();
        } catch (\Exception $e) {
            $this->assertInstanceOf(ContainerException::class, $e);
        }

        try {
            $container = ContainerBuilder::build([
                'FakeD' => [
                    'class'  => InvalidClass::class,
                    'params' => ['test']
                ]
            ])::getContainer();
        } catch (\Exception $e) {
            $this->assertInstanceOf(ContainerException::class, $e);
        }

        try {
            $container = ContainerBuilder::build([
                'FakeD' => [
                    FakeClassA::class,
                    'params' => ['test']
                ]
            ])::getContainer();
        } catch (\Exception $e) {
            $this->assertInstanceOf(ContainerException::class, $e);
        }
    }

    public
    function testGetInvalidKey() {
        $this->expectException(ContainerNotFoundException::class);
        $class = $this->container->get('RandomValue');
    }

}