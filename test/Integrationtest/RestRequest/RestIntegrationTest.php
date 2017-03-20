<?php

use chilimatic\lib\Di\ClosureFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class SigningIntegrationTest
 */
class RestIntegrationTest extends TestCase
{
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


    public function certificateDataProvider() {
        return [
            [
                '_R1-AT100_CASHBOX-DEMO-1_CASHBOX-DEMO-1-Receipt-ID-82_2016-03-11T04:24:46_0,00_0,00_0,00_0,00_0,00_NLoiSHL3bsM=_eee257579b03302f_cg8hNU5ihto=',
                'eyJhbGciOiJFUzI1NiJ9.X1IxLUFUMTAwX0NBU0hCT1gtREVNTy0xX0NBU0hCT1gtREVNTy0xLVJlY2VpcHQtSUQtODJfMjAxNi0wMy0xMVQwNDoyNDo0Nl8wLDAwXzAsMDBfMCwwMF8wLDAwXzAsMDBfTkxvaVNITDNic009X2VlZTI1NzU3OWIwMzMwMmZfY2c4aE5VNWlodG89.',
            ],
            [
                '_R1-AT100_CASHBOX-DEMO-2_CASHBOX-DEMO-1-Receipt-ID-100_2017-03-11T04:24:46_0,00_0,00_12,00_0,00_0,00_NLoiSHL3bsM=_eee257579b03302f_cg8hNU5ihto=',
                'eyJhbGciOiJFUzI1NiJ9.X1IxLUFUMTAwX0NBU0hCT1gtREVNTy0yX0NBU0hCT1gtREVNTy0xLVJlY2VpcHQtSUQtMTAwXzIwMTctMDMtMTFUMDQ6MjQ6NDZfMCwwMF8wLDAwXzEyLDAwXzAsMDBfMCwwMF9OTG9pU0hMM2JzTT1fZWVlMjU3NTc5YjAzMzAyZl9jZzhoTlU1aWh0bz0.'
            ],
            [
                '_R1-AT100_CASHBOX-DEMO-2_CASHBOX-DEMO-1-Receipt-ID-124_2017-03-11T04:24:46_0,00_0,00_12,00_0,00_0,00_NLoiSHL3bsM=_eee257579b03302f_cg8hNU5ihto=',
                'eyJhbGciOiJFUzI1NiJ9.X1IxLUFUMTAwX0NBU0hCT1gtREVNTy0yX0NBU0hCT1gtREVNTy0xLVJlY2VpcHQtSUQtMTI0XzIwMTctMDMtMTFUMDQ6MjQ6NDZfMCwwMF8wLDAwXzEyLDAwXzAsMDBfMCwwMF9OTG9pU0hMM2JzTT1fZWVlMjU3NTc5YjAzMzAyZl9jZzhoTlU1aWh0bz0.'
            ]
        ];
    }

    /**
     * @test
     */
    public function getZDA()
    {
        /**
         * @var \MED\Kassa\Model\RestConfig
         */
        $restConfig = self::$di->get('rest-config');
        $requestConfig = self::$di->get('request-config');

        $promise = self::$service->getZDA($restConfig, $requestConfig);
        /**
         * @var \GuzzleHttp\Psr7\Response $response
         */
        $response = $promise->wait();

        $zdaResponse = json_decode($response->getBody()->getContents(), true);

        self::assertEquals(['zdaid' => 'AT1'], $zdaResponse);
    }

    /**
     * @dataProvider certificateDataProvider
     * @test
     * @param string $jws
     * @param string $resultToken
     */
    public function getSignedJWS($jws, $resultToken) {

        /**
         * @var \MED\Kassa\Model\RestConfig $restConfig
         * @var \MED\Kassa\Model\RequestConfig $requestConfig
         */
        $restConfig = self::$di->get('rest-config');
        $requestConfig = self::$di->get('request-config');
        $requestConfig->setPayload($jws);

        $promise = self::$service->sendSigningRequest($restConfig, $requestConfig);
        /**
         * @var \GuzzleHttp\Psr7\Response $response
         */
        $response = $promise->wait();

        $zdaResponse = json_decode($response->getBody()->getContents(), true);
        self::assertEquals(0, strpos($zdaResponse['result'], $resultToken));
    }
}