<?php
use MED\Kassa\Model\RestConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class RestConfigTest
 */
class RestConfigTest extends TestCase
{
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\RestConfig::setApiRootUrl: $apiRootUrl has to be a string
     */
    public function constructWithInvalidParameters()
    {
        new RestConfig(12, ['test']);
    }

    /**
     * @test
     */
    public function constructWithValidParameters()
    {
        $config = new RestConfig('http://', 'v2');

        self::assertEquals('http://', $config->getApiRootUrl());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\RestConfig::setApiRootUrl: $apiRootUrl has to be a string
     */
    public function setInvalidApiRootUrl() {
        $config = new RestConfig();
        $config->setApiRootUrl([]);
    }


    /**
     * @test
     */
    public function setValidApiRootUrl() {
        $config = new RestConfig();
        $config->setApiRootUrl('http://www.myapi.com');

        self::assertEquals('http://www.myapi.com', $config->getApiRootUrl());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\RestConfig::setApiVersion: $apiVersion has to be a string
     */
    public function setInvalidApiVersion() {
        $config = new RestConfig();
        $config->setApiVersion(12);
    }

    /**
     * @test
     */
    public function setValidApiVersion() {
        $config = new RestConfig();
        $config->setApiVersion('v2');
        self::assertEquals('v2', $config->getApiVersion());
    }
}