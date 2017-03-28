<?php
use chilimatic\lib\Di\ClosureFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class SigningIntegrationTest
 */
class AesKeyTest extends TestCase
{
    use ProviderTrait;

    /**
     * @var ClosureFactory
     */
    private static $di;

    /**
     * @var \MED\Kassa\Service\RESTService
     */
    private static $service;

    public static function setUpBeforeClass()
    {
        self::$di = ClosureFactory::getInstance(__DIR__ . '/../../../src/services.php');
        self::$service = new \MED\Kassa\Service\RESTService(self::$di);

    }

    /**
     * @dataProvider keyProvider
     * @param string $key
     * @param string $comparison
     */
    public function testKeys($comparison, $key)
    {
        $sum = \MED\Kassa\Service\CryptoService::extractBytesFromHashAsBase64(
            \MED\Kassa\Service\CryptoService::generateHash($key, 'sha256'), 3
        );

        self::assertEquals($sum, $comparison);
    }
}
