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

        return self::encodeBase64(substr(hex2bin($hash), 0, $range));
    }

    /**
     * the max retries have to be above 0 ortherwise it will always fail this is just a safety measure against endless
     * recursions
     *
     * @param int $length
     *
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public static function createRandomBase64($length = 12)
    {
        if (!$length) {
            throw new \InvalidArgumentException(__METHOD__ . ': length is invalid');
        }

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+/';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * returns an array containing the key and the init vector
     *
     * @param int $maxRecursion
     * @return string
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public static function createAESKey($maxRecursion = 4)
    {
        if ($maxRecursion === 0) {
            throw new \RuntimeException(__METHOD__ . ' Reached max recursion please check');
        }

        $key = self::createRandomBase64(32);

        return self::encodeBase64(
                    self::extractBytesFromHashAsBase64(
                        bin2hex(
                            self::decodeBase64(
                                $key
                            )
                        ), 32
                    )
                );
    }

    /**
     * @param string $part1
     * @param string $part2
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function getIV($part1, $part2)
    {
        return self::extractBytesFromHashAsBase64(
            self::generateHash($part1.$part2, 'sha256'),
            16
        );
    }


    /**
     * @param string $data
     * @param string $key
     * @param int $iv
     *
     * @return string hex representation
     */
    public static function encryptAES($data, $key, $iv)
    {
        return openssl_encrypt($data, self::KEY_CYPHER, $key, 1, $iv);
    }

    /**
     * @param string $data in hex
     * @param string $key
     * @param int $iv
     *
     * @return string
     */
    public static function decryptAES($data, $key, $iv)
    {
        return openssl_decrypt($data, self::KEY_CYPHER, $key, 1, $iv);
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