<?php
use chilimatic\lib\Di\ClosureFactory;
use MED\Kassa\Decorator\BelegDecorator;
use MED\Kassa\Model\Beleg;
use MED\Kassa\Service\BelegService;
use MED\Kassa\Service\CryptoService;
use PHPUnit\Framework\TestCase;

/**
 * Class SignatureIntegrationTest
 */
class SignatureIntegrationTest extends TestCase
{
    use ProviderTrait;

    /**
     * @var ClosureFactory
     */
    private static $di;

    /**
     * set of beleg to simulate real behaviour
     * @var array
     */
    private static $belegSet = [];

    public static function setUpBeforeClass()
    {
        self::$di = ClosureFactory::getInstance(__DIR__ . '/../../../src/services.php');
    }

    /**
     * @test
     */
    public function startBeleg() {
        $belegDecorator = BelegService::buildBelegForRequest(
            [
                BelegService::TYP_INDEX => BelegDecorator::START_BELEG,
                BelegService::BELEG_INDEX => [
                    Beleg::KASSEN_ID                => 'MES-1-1',
                    Beleg::BELEG_NUMMER             => '1',
                    Beleg::ZERTIFIKAT_SERIENNUMMER  => 'a5251',
                    Beleg::BELEG_DATUM_UHRZEIT      => '2017-04-01T00:00:00',
                    Beleg::BETRAG_SATZ_NORMAL       => 0.0,
                    Beleg::BETRAG_SATZ_ERMAESSIGT_1 => 0.0,
                    Beleg::BETRAG_SATZ_ERMAESSIGT_2 => 0.0,
                    Beleg::BETRAG_SATZ_NULL         => 0.0
                ],
                BelegService::PREVBELEGJWS_INDEX => ''
            ],
            1
        );

        self::$belegSet[0]['initial'] = clone $belegDecorator;

        $builder = new \MED\Kassa\Builder\BelegManagerBuilder(self::$di);
        $requestManager = $builder->buildManager(
            self::$di->get('rest-config'),
            self::$di->get('request-config'),
            $belegDecorator
        );

        self::$belegSet[0]['signedToken'] = $requestManager->getSignedJWS();

        $parts = BelegService::extractSignedParts(self::$belegSet[0]['signedToken']);
        $expectedString = CryptoService::decodeBase64($parts[BelegService::JWS_INDEX]);
        $expectedString .= '_'. $parts[BelegService::JWS_SIGNATURE];

        self::assertEquals($expectedString, $belegDecorator->getJWS());
    }

    /**
     * @test
     */
    public function normalBeleg() {
        $oldParts = BelegService::extractSignedParts(self::$belegSet[0]['signedToken']);

        $belegDecorator = BelegService::buildBelegForRequest(
            [
                BelegService::TYP_INDEX => BelegDecorator::NORMAL_BELEG,
                BelegService::BELEG_INDEX => [
                    Beleg::KASSEN_ID                => 'MES-1-1',
                    Beleg::BELEG_NUMMER             => '2',
                    Beleg::ZERTIFIKAT_SERIENNUMMER  => 'a5251',
                    Beleg::BELEG_DATUM_UHRZEIT      => '2017-04-01T00:00:00',
                    Beleg::BETRAG_SATZ_NORMAL       => 12.0,
                    Beleg::BETRAG_SATZ_ERMAESSIGT_1 => 0.0,
                    Beleg::BETRAG_SATZ_ERMAESSIGT_2 => 0.0,
                    Beleg::BETRAG_SATZ_NULL         => 0.0,
                    Beleg::STAND_UMSATZZAEHLER      => 1200
                ],
                BelegService::PREVBELEGJWS_INDEX => $oldParts[BelegService::JWS_INDEX]
            ],
            1
        );

        self::$belegSet[1]['initial'] = clone $belegDecorator;

        $builder = new \MED\Kassa\Builder\BelegManagerBuilder(self::$di);
        $requestManager = $builder->buildManager(
            self::$di->get('rest-config'),
            self::$di->get('request-config'),
            $belegDecorator
        );

        self::$belegSet[1]['signedToken'] = $requestManager->getSignedJWS();

        $parts = BelegService::extractSignedParts(self::$belegSet[1]['signedToken']);

        $expectedString = CryptoService::decodeBase64($parts[BelegService::JWS_INDEX]);
        $expectedString .= '_'. $parts[BelegService::JWS_SIGNATURE];

        self::assertEquals(
            $expectedString,
            $belegDecorator->getJWS()
        );
    }

}
