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
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Service\CryptoService::createRandomBase64: length is invalid
     */
    public function checkInvalidLengthForRandomKey() {
        CryptoService::createRandomBase64(0);
    }

    /**
     * @test
     */
    public function checkStringLengthRandomKey() {
        $key = CryptoService::createRandomBase64(12);
        self::assertSame(strlen($key), 12);
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
        self::assertEquals($expectedHash, $hash);
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
        self::assertEquals($expectedString, $result);
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

    /**
     * @test
     */
    public function checkBase64EncodingNoUrl() {
        $encodedString = CryptoService::encodeBase64('myString', false);
        self::assertEquals('bXlTdHJpbmc=', $encodedString);
    }

    /**
     * @test
     */
    public function checkBase64EncodingUrl() {
        $encodedString = CryptoService::encodeBase64('myString', true);
        self::assertEquals('bXlTdHJpbmc', $encodedString);
    }

    /**
     * @test
     */
    public function checkBase64DecodingNoUrl() {
        $decodedString = CryptoService::decodeBase64('bXlTdHJpbmc=', false);
        self::assertEquals('myString', $decodedString);
    }

    /**
     * @test
     */
    public function checkBase64DecodingUrl() {
        $decodedString = CryptoService::decodeBase64('bXlTdHJpbmc', true);
        self::assertEquals('myString', $decodedString);
    }

    /**
     * @test
     */
    public function checkBase65EnAndDecoding() {
        $string = 'test';
        self::assertEquals(
            $string,
            CryptoService::decodeBase64(
                CryptoService::encodeBase64($string)
            )
        );
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage MED\Kassa\Service\CryptoService::createAESKey Reached max recursion please check
     */
    public function generateAesKeyReachMaxRecursions() {
        CryptoService::createAESKey(0);
    }

    /**
     * @test
     */
    public function generateAesKey() {
        $keySet = CryptoService::createAESKey();
        self::assertNotEmpty($keySet);
    }


    /**
     * @dataProvider initializationVectorProvider
     * @test
     */
    public function encryptAes($kassenId, $belegNummer, $string) {
        $key = CryptoService::createAESKey();

        $iv = CryptoService::getIV($kassenId, $belegNummer);
        $encryptedString = CryptoService::encryptAES($string, base64_decode($key), base64_decode($iv));
        $decryptedString = CryptoService::decryptAES($encryptedString, base64_decode($key), base64_decode($iv));
        self::assertEquals($decryptedString, $string);
    }

    /**
     * @test
     */
    public function IVvsJavaIV() {
        $iv = CryptoService::getIV('MES-1-1', '000001');
        self::assertEquals('eAlTcVwvYZ+p8gLd16QBvw==', $iv);
    }

    /**
     * @test
     */
    public function getAesEncryptedValue() {
        $key = "88gl/+qW7Sz/UUBq6RgTDnKX5iCE5ZSkbEu2A7yWTzM=";
        $kassaID = 'MES-1-1';
        $belegID = '001054';
        $lenght = 8;

        $base = $kassaID.$belegID;
        $iv = CryptoService::getIV($kassaID, $belegID);
        $hash = CryptoService::encryptAES(
            base64_decode('AAAAAAAAAAA='),
            base64_decode($key),
            base64_decode($iv)
        );

        self::assertEquals("MES-1-1001054", $base);
        self::assertEquals('8LI/5bDm1c9igraTbumBvw==', $iv);
        self::assertEquals("AQcw/Xa9/JM=", base64_encode($hash));
    }

    /**
     * @test
     */
    public function createPaddedNumber0() {
        $res = pack('N', 0) . pack('N', 0);
        self::assertEquals('AAAAAAAAAAA=', $res);
    }

    /**
     * @test
     */
    public function createPaddedNumber100() {
        $wert100 = 'AAAAAAAAAGQ=';

        $res = pack('N', 0) . pack('N', 100);
        self::assertEquals($wert100, base64_encode($res));
    }

    /**
     * @test
     * @dataProvider signatureExtractionProvider
     * @param $signedJWS
     * @param $extractHash
     */
    public function correctSignatureExtraction($signedJWS, $extractHash)
    {
        $part =
            CryptoService::generateHash(
                $signedJWS, 'sha256'
            );

        $result = CryptoService::extractBytesFromHashAsBase64(
                $part,
                8
        );
        self::assertEquals($result, $extractHash);
    }

    /**
     * @test
     */
    public function correctInitialChain() {
        $value = 'DEMO-CASH-BOX524';

        $result = CryptoService::extractBytesFromHashAsBase64(
            CryptoService::generateHash(
                $value,
                'sha256'
            ),
            8
        );

        self::assertEquals('lDUkNhEeJKY=', $result);
    }


    /**
     * @test
     */
    public function correctInitialBelegHash() {
        $hash = CryptoService::generateHash('A12347', 'sha256');
        $extractedHash = CryptoService::extractBytesFromHashAsBase64($hash, 8);;

        self::assertEquals('OeSKQjO4zKI=', $extractedHash);
    }
}