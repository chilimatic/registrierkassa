<?php
use chilimatic\lib\Di\ClosureFactory;
use MED\Kassa\Service\RESTService;
use PHPUnit\Framework\TestCase;

/**
 * Class RESTServiceTest
 */
class RESTServiceTest extends TestCase
{
    /**
     * @var RESTService
     */
    private static $service;

    /**
     *
     */
    public static function setUpBeforeClass()
    {
        self::$service = new RESTService(
            ClosureFactory::getInstance(__DIR__ . '/../../../src/services.php')
        );
    }

    /**
     * @test
     */
    public function checkConstructor() {
        $service = new RESTService(
            ClosureFactory::getInstance(__DIR__ . '/../../../src/services.php')
        );

        self::assertInstanceOf(\GuzzleHttp\Client::class, $service->getHttpClient());
    }

    /**
     * @test
     */
    public function validateUrlBuilder()
    {
        $restConfig = new \MED\Kassa\Model\RestConfig(
            'http://www.test.com',
            'v2',
            [
                'sign' => 'test'
            ]
        );

        $url = self::$service->urlBuilder($restConfig, RESTService::SIGN_ACTION, '12');

        self::assertEquals('http://www.test.com/v2/12/test', $url);
    }


}