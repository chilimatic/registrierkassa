<?php
use MED\Kassa\Model\Signature;
use PHPUnit\Framework\TestCase;

/**
 * Class SignatureTest
 */
class SignatureTest extends TestCase
{
    use ProviderTrait;

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\Signature::__construct the pos variable has to be within 0 and 10
     */
    public function checkConstructionWithWrongType() {
        new Signature('test');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\Signature::__construct the pos variable has to be within 0 and 10
     */
    public function checkConstructionWithCorrectTypeOutOfRange() {
       new Signature(1000000);
    }

    /**
     * @test
     * @dataProvider signaturePrefixProvider
     *
     * @param int $pos
     * @param string $prefix
     */
    public function signaturePrefix($pos, $prefix) {
        $signature = new Signature($pos);
        self::assertEquals($signature->getSignaturePrefix(), $prefix);
    }

}