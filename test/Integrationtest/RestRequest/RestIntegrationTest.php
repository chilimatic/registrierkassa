<?php
use chilimatic\lib\Di\ClosureFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class SigningIntegrationTest
 */
class RestIntegrationTest extends TestCase
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