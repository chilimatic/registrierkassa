<?php
use MED\Kassa\Model\Beleg;
use MED\Kassa\Decorator\BelegDecorator;
use MED\Kassa\Model\Signature;
use PHPUnit\Framework\TestCase;

/**
 * Class BelegDecorator
 */
class BelegDecoratorTest extends TestCase
{
    use ProviderTrait;

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Decorator\BelegDecorator::setTyp type has to be valid please check the constants in the class
     */
    public function wrongTypeInConstructor()
    {
        new BelegDecorator(new Beleg(), new Signature(Signature::A_TRUST_POS), 12);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Decorator\BelegDecorator::setTyp type has to be valid please check the constants in the class
     */
    public function changeToWrongType() {
        $beleg = new BelegDecorator(new Beleg(), new Signature(Signature::A_TRUST_POS) , BelegDecorator::NORMAL_BELEG);
        $beleg->setTyp(12);
    }

    /**
     * @test
     */
    public function setPrepared() {
        $beleg = new BelegDecorator(new Beleg(), new Signature(Signature::A_TRUST_POS), BelegDecorator::NORMAL_BELEG);
        $beleg->setToPrepared();
        self::assertTrue($beleg->isPrepared());
    }


    /**
     * @test
     * @dataProvider dataProviderBelegToString
     * @param $dataSet
     */
    public function getJWS($dataSet, $belegJWS, $signatureIndex, $belegPrefix) {
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

        $decorator = new BelegDecorator(
            $beleg,
            new Signature($signatureIndex),
            BelegDecorator::NORMAL_BELEG
        );


        self::assertEquals($decorator->getJWS(), $belegPrefix . $belegJWS);
    }
}