<?php
use MED\Kassa\Decorator\BelegDecorator;
use MED\Kassa\Model\Beleg;
use MED\Kassa\Model\Signature;
use MED\Kassa\Service\BelegService;
use PHPUnit\Framework\TestCase;

/**
 * Class BelegServiceTest
 */
class BelegServiceTest extends TestCase {

    /**
     * @return array
     */
    public function belegDataProvider() {
        return [
            [
                [
                    Beleg::KASSEN_ID => '1',
                    Beleg::BELEG_NUMMER => '',
                    Beleg::BELEG_DATUM_UHRZEIT => date('Y-m-d'). 'T' . date('H:i:s'),
                    Beleg::BETRAG_SATZ_NORMAL => 0,
                    Beleg::BETRAG_SATZ_NULL => 0,
                    Beleg::BETRAG_SATZ_ERMAESSIGT_1 => 0,
                    Beleg::BETRAG_SATZ_ERMAESSIGT_2 => 0,
                    Beleg::BETRAG_SATZ_BESONDERS => 0,
                    Beleg::ZERTIFIKAT_SERIENNUMMER => 0,
                    Beleg::SIG_VORIGER_BELEG => '1',
                    Beleg::STAND_UMSATZZAEHLER => 0
                ],
                '1'
            ]
        ];
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Service\BelegService::createNewBeleg $data should not be empty
     */
    public function emptyDataCreateBeleg() {
        $service = new BelegService();
        $service->createNewBeleg([]);
    }

    /**
     * @test
     */
    public function wrongDataCreateBeleg() {
        $service = new BelegService();
        $beleg = $service->createNewBeleg(['randomdata']);

        self::assertEquals($beleg->getBelegNummer(), '');
    }

    /**
     * @test
     * @dataProvider belegDataProvider
     * @param array $data
     * @param string $expectedKassenId
     */
    public function validDataCreateBeleg($data, $expectedKassenId) {
        $service = new BelegService();
        $beleg = $service->createNewBeleg($data);

        self::assertEquals($beleg->getKassenId(), $expectedKassenId);
    }

    /**
     * @test
     * @dataProvider belegDataProvider
     * @param array $data
     */
    public function decorateBeleg($data) {
        $service = new BelegService();
        $beleg = $service->decorateBeleg(
            $service->createNewBeleg($data),
            new Signature(Signature::A_TRUST_POS),
            BelegDecorator::NORMAL_BELEG
        );

        self::assertFalse($beleg->isPrepared());
    }
}
