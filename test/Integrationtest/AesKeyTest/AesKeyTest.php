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


    public function keyProvider() {
        return [
            [
                'MPnJ','MPnJD+mYZ1XmhaaqiJnS7M/470PBbVF4HioJGogJLC0='
            ],
            [
                'UgZx','UgZx6JW1z6Z1Xv8Bo5J2LEEpm65O+0PLwdv31//Bvoc=',
            ],
            [
                'o/n/','o/n/WmtZ9aALG2VUYzywSsrw3SLZd4sorSsdQ5xNeF8=',
            ],
            [
                'qpZl','qpZl6zI9EdbOtqaq8cQhAYVedxOs48IYT4TPY+XDHb8=',
            ],
            [
                'huT2','huT2qAwHivT49+dtwFcoPLBet1C8UeiXJWCBPtfxnW4=',
            ],
            [
                'ZyMP','ZyMPwrlUSRsziDsMFmuE1E4nMYO95NtrbKyuTz94o1Y=',
            ],
            [
                'hGRt','hGRtLGyVjhVTbUYIqMIKm0Q+EEMKIDbHgHSagP+gHX8=',
            ],
            [
                'kNz8','kNz85AsN+8/mCNeBeyuzhVYhQ4rx1Fy5mi8hqJuLkVs=',
            ],
            [
                'rxEy','rxEymy9B7x8IWeWCYgIJqtdeGbQGMFXVbkV9CiWqwLk=',
            ],
            [
                'y1S1','y1S1VND6cRYXNofyoQ/9kwTcnLvjOpfhVGyMq7iAT40=',
            ],
            [
                'bhKQ','bhKQ198BftnuHqjZg9Gspdy1fyQW5rSBvS1hOiEvSwU=',
            ],
            [
                '6fpU','6fpUrXlopfxJW/S9PpVCFMLnrpdAkfh/pSIUrL9fWO0=',
            ]
        ];
    }

    /**
     * @dataProvider keyProvider
     * @param string $key
     * @param string $comparison
     */
    public function testKeys($comparison, $key)
    {
        $sum = \MED\Kassa\Service\CryptoService::extractBytesFromHashAsBase64(
            bin2hex(base64_decode($key)), 3
        );

        self::assertEquals($sum, $comparison);
    }
}
