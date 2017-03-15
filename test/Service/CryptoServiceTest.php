<?php
use MED\Kassa\Service\CryptoService;
use PHPUnit\Framework\TestCase;

/**
 * Class CryptoServiceTest
 */
class CryptoServiceTest extends TestCase
{
    use ProviderTrait;
    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage MED\Kassa\Service\CryptoService::createAESKey Max retries have been reached
     */
    public function checkMaxRetries() {
        CryptoService::createAESKey(0);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Service\CryptoService::generateHash: hashbase 0 is not a string
     */
    public function getIvWithWrongInput() {
        CryptoService::generateHash(0, 'sha256');
    }

    /**
     * @test
     * @dataProvider hashBaseProvider
     * @param string $hashBase
     * @param string $algorithm
     * @param string $expectedHash
     */
    public function checkHashgeneration($hashBase, $algorithm, $expectedHash) {
        $hash = CryptoService::generateHash($hashBase, $algorithm);
        self::assertEquals($hash, $expectedHash);
    }

    /**
     * @test
     * @dataProvider hashProvider
     * @param string $hash
     * @param int $amountBytes
     * @param string $expectedString
     */
    public function checkBitExtraction($hash, $amountBytes, $expectedString) {
        $result = CryptoService::extractBytesFromHashAsBase64($hash, $amountBytes);
        self::assertEquals($result, $expectedString);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Service\CryptoService::extractBytesFromHashAsBase64: hash: 123.2 is not a string or amount: 5 is not an int
     */
    public function checkBitExtractionWithWrongHashInput() {
        CryptoService::extractBytesFromHashAsBase64(123.2, 5);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Service\CryptoService::extractBytesFromHashAsBase64: hash: test is not a string or amount: test is not an int
     */
    public function checkBitExtractionWithWrongAmountInput() {
        CryptoService::extractBytesFromHashAsBase64('test', 'test');
    }
}