<?php
use MED\Kassa\Model\Signature;
use PHPUnit\Framework\TestCase;

/**
 * Class SignatureTest
 */
class SignatureTest extends TestCase
{
    /**
     * @return array
     */
    public function signaturePrefixProvider() {
        return [
            [
                0, 'R1-AT0',
                1, 'R1-AT1',
                2, 'R1-AT2',
                3, 'R1-AT3',
            ]
        ];
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\Signature::__construct the pos variable has to be within 0 and 10
     */
    public function checkConstructionWithWrongType() {
        $signature = new Signature('test');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage MED\Kassa\Model\Signature::__construct the pos variable has to be within 0 and 10
     */
    public function checkConstructionWithCorrectTypeOutOfRange() {
        $signature = new Signature(1000000);
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