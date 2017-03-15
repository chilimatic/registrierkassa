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


    /**
     * @test
     * @dataProvider signaturePrefixProvider
     * @param int $pos
     */
    public function signatureAlgorithmJson($pos) {
        $service = new BelegService();

        $signature = new Signature($pos);
        self::assertEquals(
            base64_encode(json_encode(
                [
                    'alg' => $signature->getJwsSignatureAlgorithm()
                ]
            )),
            $service->getSignatureAlgorithmJsonAsBase64($signature)
        );
    }
}
