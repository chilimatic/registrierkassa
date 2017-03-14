<?php
namespace MED\Kassa\Model;

class Signature
{
    const SIGNATURE_PREFIX = 'R';
    const A_TRUST_POS = 1;

    const ENCRYPTION_ID_POS = 0;
    const ZDA_ID_POS = 1;
    const JWS_HASH_POS = 2;
    const PREV_SIG_ALGO_POS = 3;
    const NUM_BYTES_EXTRACTED_POS = 4;

    private static $signatureSet = [
        ['1', 'AT0', 'ES256', 'SHA-256', 8],
        ['1', 'AT1', 'ES256', 'SHA-256', 8],
        ['1', 'AT2', 'ES256', 'SHA-256', 8],
        ['1', 'AT3', 'ES256', 'SHA-256', 8],
        ['1', 'AT4', 'ES256', 'SHA-256', 8],
        ['1', 'AT5', 'ES256', 'SHA-256', 8],
        ['1', 'AT6', 'ES256', 'SHA-256', 8],
        ['1', 'AT7', 'ES256', 'SHA-256', 8],
        ['1', 'AT8', 'ES256', 'SHA-256', 8],
        ['1', 'AT9', 'ES256', 'SHA-256', 8],
        ['1', 'AT10', 'ES256', 'SHA-256', 8],
    ];

    /**
     * @var int
     */
    private $pos;

    /**
     * Signature constructor.
     * @param int $pos
     * @throws \InvalidArgumentException
     */
    public function __construct($pos)
    {
        if (!is_int($pos) || !isset(self::$signatureSet[$pos])) {
            throw new \InvalidArgumentException(__METHOD__ . ' the pos variable has to be within 0 and ' . (string) (count(self::$signatureSet)-1));
        }

        $this->pos = $pos;
    }

    /**
     * @return string
     */
    public function getSignaturePrefix() {
        return self::SIGNATURE_PREFIX . self::$signatureSet[$this->pos][self::ENCRYPTION_ID_POS]
            . '-' . self::$signatureSet[$this->pos][self::ZDA_ID_POS];
    }

    /**
     * @return string
     */
    public function getJwsSignatureAlgorithm() {
        return self::$signatureSet[$this->pos][self::JWS_HASH_POS];
    }

    /**
     * @return string
     */
    public function getPreviousValueHashAlgorithm() {
        return self::$signatureSet[$this->pos][self::PREV_SIG_ALGO_POS];
    }

    /**
     * @return int
     */
    public function getNumberOfExtractedBytesFromPrevSigHash() {
        return self::$signatureSet[$this->pos][self::NUM_BYTES_EXTRACTED_POS];
    }
}