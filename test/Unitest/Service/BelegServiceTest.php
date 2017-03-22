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
     * @dataProvider belegDataProviderWithSignature
     * @param array $data
     * @param string $expectedKassenId
     */
    public function validDataCreateBeleg($data, $expectedKassenId) {
        $beleg = BelegService::createNewBeleg($data);

        self::assertEquals($beleg->getKassenId(), $expectedKassenId);
    }

    /**
     * @test
     * @dataProvider belegDataProviderWithSignature
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

    /**
     * @test
     * @dataProvider belegDataProviderWithSignature
     * @param array $data
     * @param int $signaturePosition
     * @param string $expectedBase64Signature
     */
    public function getJWSSignature($data, $signaturePosition, $expectedBase64Signature)
    {
        $belegDecorator = BelegService::decorateBeleg(
            BelegService::createNewBeleg($data),
            new Signature($signaturePosition),
            BelegDecorator::NORMAL_BELEG
        );

        self::assertEquals(BelegService::getJWSSignature($belegDecorator), $expectedBase64Signature);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Service\BelegService::buildBelegForRequest: is missing mandatory fields please check:
     */
    public function buildBelegForRequestWithInvalidBelegInput() {
        BelegService::buildBelegForRequest([], 12);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\Signature::__construct the pos variable has to be within 0 and 10
     */
    public function buildBelegForRequestWithInvalidPos() {
        BelegService::buildBelegForRequest(
            [
                BelegService::TYP_INDEX             => '',
                BelegService::BELEG_INDEX           => [],
                BelegService::PREVBELEGJWS_INDEX    => ''
            ],
            12
        );
    }

    /**
     * @test
     * @dataProvider buildDataProvider
     * @param array $belegData
     * @param int $signaturePos
     * @param string $expectedJWS
     */
    public function buildBelegRequest($belegData, $signaturePos, $expectedJWS) {
        $belegDecorator = BelegService::buildBelegForRequest($belegData, $signaturePos);

        self::assertEquals($expectedJWS, $belegDecorator->getJWS());
    }

    /**
     * @test
     * @dataProvider certificateDataProvider
     * @param string $jws
     * @param string $encryptedJws
     */
    public function splitBelegData($jws, $encryptedJws) {
        $parts = BelegService::extractSignedParts($encryptedJws);
        $decodedHead = base64_decode($parts[BelegService::JWS_HEAD_INDEX]);
        self::assertEquals('{"alg":"ES256"}', $decodedHead);
    }
}
