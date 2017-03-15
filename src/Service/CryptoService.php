<?php
namespace MED\Kassa\Service;

/**
 * Class CryptoService
 * @package MED\Kassa\Service
 */
class CryptoService
{
    const KEY_CYPHER = 'AES-256-CTR';
    const STRING_ENCODING = 'UTF-8';

    /**
     * @param $hashBase
     * @param $hashAlgorithm
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function generateHash($hashBase, $hashAlgorithm)
    {
        if (!is_string($hashBase)) {
            throw new \InvalidArgumentException(__METHOD__ . ": hashbase $hashBase is not a string");
        }

        $base = utf8_encode($hashBase);
        return hash($hashAlgorithm, $base);
    }

    /**
     * @param string $hash
     * @param int $range
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function extractBytesFromHashAsBase64($hash, $range)
    {
        if (!is_string($hash) || !is_int($range)) {
            throw new \InvalidArgumentException(__METHOD__ . ": hash: $hash is not a string or amount: $range is not an int");
        }

        return base64_encode(mb_substr(hex2bin($hash), 0, $range, self::STRING_ENCODING));
    }

    /**
     * the max retries have to be above 0 ortherwise it will always fail this is just a safety measure against endless
     * recursions
     *
     * @param int $maxTry
     * @return string
     * @throws \RuntimeException
     */
    public static function createAESKey($maxTry = 12)
    {
        if ($maxTry <= 0) {
            throw new \RuntimeException(__METHOD__ . ' Max retries have been reached');
        }

        $ivlen = openssl_cipher_iv_length(self::KEY_CYPHER);
        $isCryptoStrong = false; // Will be set to true by the function if the algorithm used was cryptographically secure
        $key = openssl_random_pseudo_bytes($ivlen, $isCryptoStrong);
        if ($key && $isCryptoStrong) {
            return base64_encode($key);
        }

        return self::createAESKey(--$maxTry);
    }

    /**
     * @param string $input
     * @param bool $asUrl
     * @return string
     */
    public static function encodeBase64($input, $asUrl = false) {
        if ($asUrl) {
            return self::base64urlEncode($input);
        } else {
            return base64_encode($input);
        }
    }

    /**
     * @param string $input
     * @param bool $asUrl
     * @return string
     */
    public static function decodeBase64($input, $asUrl = false) {
        if ($asUrl) {
            return self::base64urlDecode($input);
        } else {
            return base64_decode($input);
        }
    }

    /**
     * @param string $data
     * @return string
     */
    public static function base64urlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * @param string $data
     * @return string
     */
    public static function base64urlDecode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}