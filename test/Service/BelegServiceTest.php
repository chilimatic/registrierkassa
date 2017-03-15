<?php
use MED\Kassa\Decorator\BelegDecorator;
use MED\Kassa\Model\Signature;
use MED\Kassa\Service\BelegService;
use PHPUnit\Framework\TestCase;

/**
 * Class BelegServiceTest
 */
class BelegServiceTest extends TestCase
{
    use ProviderTrait;
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Service\BelegService::createNewBeleg $data should not be empty
     */
    public function emptyDataCreateBeleg() {
        BelegService::createNewBeleg([]);
    }

    /**
     * @test
     */
    public function wrongDataCreateBeleg() {
        $beleg = BelegService::createNewBeleg(['randomdata']);

        self::assertEquals($beleg->getBelegNummer(), '');
    }

    /**
     * @test
     * @dataProvider belegDataProvider
     * @param array $data
     * @param string $expectedKassenId
     */
    public function validDataCreateBeleg($data, $expectedKassenId) {
        $beleg = BelegService::createNewBeleg($data);

        self::assertEquals($beleg->getKassenId(), $expectedKassenId);
    }

    /**
     * @test
     * @dataProvider belegDataProvider
     * @param array $data
     */
    public function decorateBeleg($data) {

        $beleg = BelegService::decorateBeleg(
            BelegService::createNewBeleg($data),
            new Signature(Signature::A_TRUST_POS),
            BelegDecorator::NORMAL_BELEG
        );

        self::assertFalse($beleg->isPrepared());
    }


    /**
     * @test
     * @dataProvider signaturePrefixProvider
     * @param int $pos
     */
    public function signatureAlgorithmJson($pos) {
        $signature = new Signature($pos);
        self::assertEquals(
            json_encode(
                [
                    'alg' => $signature->getJwsSignatureAlgorithm()
                ]
            ),
            BelegService::getSignatureAlgorithmJson($signature)
        );
    }
}
