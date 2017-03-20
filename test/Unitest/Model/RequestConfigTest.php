<?php
use MED\Kassa\Model\RequestConfig;
use PHPUnit\Framework\TestCase;

/**
 * Class RequestTest
 */
class RequestConfigTest extends TestCase
{
    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\RequestConfig::setName: $name has to be a string and cannot be empty. Given: 12
     */
    public function constructWithInvalidParameters()
    {
        new RequestConfig(12);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\RequestConfig::setName: $name has to be a string and cannot be empty. Given: 12
     */
    public function setInvalidName() {
        $request = new RequestConfig();
        $request->setName(12);
    }

    /**
     * @test
     */
    public function setValidName() {
        $request = new RequestConfig();
        $request->setName('12');

        self::assertEquals('12', $request->getName());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\RequestConfig::setPassword: $password has to be a string . Given: 12
     */
    public function setInvalidPassword() {
        $request = new RequestConfig();
        $request->setPassword(12);
    }

    /**
     * @test
     */
    public function setValidPassword() {
        $request = new RequestConfig();
        $request->setPassword('12');

        self::assertEquals('12', $request->getPassword());
    }


    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\RequestConfig::setPayload: $payload has to be a string and cannot be empty. Given: 12
     */
    public function setInvalidPayLoad() {
        $request = new RequestConfig();
        $request->setPayload(12);
    }

    /**
     * @test
     */
    public function setValidPayload() {
        $request = new RequestConfig();
        $request->setPassword('12');

        self::assertEquals('12', $request->getPassword());
    }
}