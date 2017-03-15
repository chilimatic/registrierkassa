<?php
use MED\Kassa\Model\Beleg;
use PHPUnit\Framework\TestCase;

/**
 * Class BelegTest
 */
class BelegTest extends TestCase
{
    use ProviderTrait;

    /**
     * @test
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage property overloading prohibited
     */
    public function propertyOverloading() {
        $beleg = new Beleg();
        $beleg->randomProperty = 12;
    }


    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\Beleg::setBetragSatzNormal value has to be a number
     */
    public function betragSatzNormalIsNumericTest() {
        $beleg = new Beleg();
        $beleg->setBetragSatzNormal('test');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\Beleg::setBetragSatzBesonders value has to be a number
     */
    public function betragSatzBesondersIsNumericTest() {
        $beleg = new Beleg();
        $beleg->setBetragSatzBesonders('test');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\Beleg::setBetragSatzNull value has to be a number
     */
    public function betragSatzNullIsNumericTest() {
        $beleg = new Beleg();
        $beleg->setBetragSatzNull('test');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\Beleg::setBetragSatzErmaessigt1 value has to be a number
     */
    public function betragSatzErmaessigt1IsNumericTest() {
        $beleg = new Beleg();
        $beleg->setBetragSatzErmaessigt1('test');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\Beleg::setBetragSatzErmaessigt2 value has to be a number
     */
    public function betragSatzErmaessigt2IsNumericTest() {
        $beleg = new Beleg();
        $beleg->setBetragSatzErmaessigt2('test');
    }


    /**
     * @test
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage property overloading prohibited
     */
    public function propertyOverloadingIsset() {
        $beleg = new Beleg();
        isset($beleg->randomProperty);
    }


    /**
     * @test
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage property overloading prohibited
     */
    public function propertyOverloadingGet() {
        $beleg = new Beleg();
        $beleg->randomProperty;
    }

    /**
     * @test
     * @dataProvider dataProviderBelegToString
     * @param $dataSet
     * @param $expectedString
     */
    public function toStringCheck($dataSet, $expectedString) {
        $beleg = new Beleg();
        $beleg
            ->setKassenId($dataSet[0])
            ->setBelegNummer($dataSet[1])
            ->setBelegDatumUhrzeit($dataSet[2])
            ->setBetragSatzNormal($dataSet[3])
            ->setBetragSatzErmaessigt1($dataSet[4])
            ->setBetragSatzErmaessigt2($dataSet[5])
            ->setBetragSatzNull($dataSet[6])
            ->setBetragSatzBesonders($dataSet[7])
            ->setStandUmsatzZaehlerAES256ICM($dataSet[8])
            ->setZertifikatSerienNummer($dataSet[9])
            ->setSigVorherigerBeleg($dataSet[10]);

        self::assertEquals($expectedString, $beleg->getJWS());
    }
}