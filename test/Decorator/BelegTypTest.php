<?php
use MED\Kassa\Model\Beleg;
use MED\Kassa\Decorator\BelegDecorator;
use PHPUnit\Framework\TestCase;

/**
 * Class BelegDecorator
 */
class BelegDecoratorTest extends TestCase
{

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Decorator\BelegDecorator::setTyp type has to be valid please check the constants in the class
     */
    public function wrongTypeInConstructor()
    {
        $beleg = new BelegDecorator(new Beleg(), 12);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Decorator\BelegDecorator::setTyp type has to be valid please check the constants in the class
     */
    public function changeToWrongType() {
        $beleg = new BelegDecorator(new Beleg(), BelegDecorator::NORMAL_BELEG);
        $beleg->setTyp(12);
    }

    /**
     * @test
     */
    public function setPrepared() {
        $beleg = new BelegDecorator(new Beleg(), BelegDecorator::NORMAL_BELEG);
        $beleg->setToPrepared();
        self::assertTrue($beleg->isPrepared());
    }
}