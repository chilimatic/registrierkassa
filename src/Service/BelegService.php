<?php
namespace MED\Kassa\Service;

use MED\Kassa\Decorator\BelegDecorator;
use MED\Kassa\Model\Beleg;
use MED\Kassa\Model\Signature;
use MED\Kassa\Traits\SetPropertiesTrait;

/**
 * Class BelegService
 * @package MED\Kassa\Service
 */
class BelegService
{
    use SetPropertiesTrait;

    /**
     * indexes for input arrays
     */
    const BELEG_INDEX = 'beleg';
    const TYP_INDEX = 'typ';
    const PREVBELEGJWS_INDEX = 'prevBelegJWS';

    const JWS_HEAD_INDEX = 'jws-head';
    const JWS_INDEX = 'jws';
    const JWS_SIGNATURE = 'jws-signed';

    /**
     * @param array $data
     *
     * @return Beleg
     * @throws \InvalidArgumentException
     */
    public static function createNewBeleg(array $data)
    {
        if (!$data) {
            throw new \InvalidArgumentException(__METHOD__ . ' $data should not be empty');
        }

        return self::setProperties(new Beleg(), $data);
    }

    /**
     * @param Beleg $beleg
     * @param Signature $signature
     * @param string $type
     * @return BelegDecorator
     *
     * @throws \InvalidArgumentException
     */
    public static function decorateBeleg(Beleg $beleg, Signature $signature, $type)
    {
        return new BelegDecorator($beleg, $signature, $type);
    }

    /**
     * @param Signature $signature
     * @return string
     */
    public static function getSignatureAlgorithmJson(Signature $signature) {
        return json_encode(['alg' => $signature->getJwsSignatureAlgorithm()]);
    }

    /**
     * @param BelegDecorator $belegDecorator
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function getJWSSignature(BelegDecorator $belegDecorator)
    {
        $signatureHeader = self::getSignatureAlgorithmJson($belegDecorator->getSignature());
        $signaturePayload = $belegDecorator->getJWS();
        return CryptoService::base64urlEncode($signatureHeader.$signaturePayload);
    }

    /**
     * @param string $vorherigerJWSHash
     * @param Signature $signature
     * @param string $kassenId
     * @return string
     *
     * @throws \InvalidArgumentException
     * @internal param BelegDecorator $belegDecorator
     */
    public static function generateChainingValue($vorherigerJWSHash, Signature $signature, $kassenId = null)
    {
        if (($kassenId === null || $kassenId === '') && !$vorherigerJWSHash) {
            throw new \InvalidArgumentException(__METHOD__ . ': $vorherigerJWSHash and kassenId is empty');
        }

        if (!$vorherigerJWSHash) {
            $hashBase = (string) $kassenId;
        } else {
            $hashBase = (string) $vorherigerJWSHash;
        }

        $phpAlgorithm = strtolower(str_replace('-', '', $signature->getPreviousValueHashAlgorithm()));

        return CryptoService::extractBytesFromHashAsBase64(
            CryptoService::generateHash($hashBase, $phpAlgorithm),
            $signature->getNumberOfExtractedBytesFromPrevSigHash()
        );
    }


    /**
     * inputdata should be as follows
     * [
     *  BelegService::TYP_INDEX => BelegDecorator::TRAININGS_BELEG | BelegDecorator::STORNO_BELEG | BelegDecorator::NULL_BELEG | BelegDecorator::NORMAL_BELEG | BelegDecorator::START_BELEG
     *  BelegService::BELEG_INDEX => [
     *          Beleg::KASSEN_ID                => 'CUSTOMPREFIX-POSID-CASHIERID',
     *          Beleg::BELEG_NUMMER             => '000001',
     *          Beleg::ZERTIFIKAT_SERIENNUMMER  => 'ID-ENCRYPTION-CERT',
     *          Beleg::BELEG_DATUM_UHRZEIT      => 'YYYY-mm-dd\TH:i:s',
     *          Beleg::BETRAG_SATZ_NORMAL       => 10.0,
     *          Beleg::BETRAG_SATZ_ERMAESSIGT_1 => 0.0,
     *          Beleg::BETRAG_SATZ_ERMAESSIGT_2 => 0.0,
     *          Beleg::BETRAG_SATZ_NULL         => 0.0,
     *          Beleg::STAND_UMSATZZAEHLER      => 'AES ENCRYPTED THAN BASE64 ENCRYPTED TOTAL AMOUNT IN CENT OR SPECIAL CASES AS REFERED IN THE DOCUMENTATION',
     *  ],
     *  BelegService::PREVBELEGJWS_INDEX => 'HASH FROM LAST JWS'
     * ]
     *
     *
     * @param array $belegData
     * @param int $signaturePos
     * @return BelegDecorator
     * @throws \InvalidArgumentException
     */
    public static function buildBelegForRequest(array $belegData, $signaturePos)
    {
        if (!isset($belegData[self::TYP_INDEX], $belegData[self::PREVBELEGJWS_INDEX], $belegData[self::BELEG_INDEX])) {
            throw new \InvalidArgumentException(
                __METHOD__
                . ': is missing mandatory fields please check: '
                . print_r($belegData, true)
            );
        }

        switch ($belegData[self::TYP_INDEX]) {
            case BelegDecorator::NULL_BELEG:
            case BelegDecorator::START_BELEG:
                $belegData[self::BELEG_INDEX][Beleg::STAND_UMSATZZAEHLER] = CryptoService::encodeBase64(decbin(0));
                break;
            case BelegDecorator::STORNO_BELEG:
                $belegData[self::BELEG_INDEX][Beleg::STAND_UMSATZZAEHLER] = Beleg::STORNO_BELEG_VALUE;
                break;
            case BelegDecorator::TRAININGS_BELEG:
                $belegData[self::BELEG_INDEX][Beleg::STAND_UMSATZZAEHLER] = Beleg::TRAININGS_BELEG_VALUE;
                break;
            default:
                $value = CryptoService::encodeBase64(
                    decbin($belegData[self::BELEG_INDEX][Beleg::STAND_UMSATZZAEHLER])
                );
                $belegData[self::BELEG_INDEX][Beleg::STAND_UMSATZZAEHLER] = $value;
                break;
        }

        $signature = new Signature($signaturePos);

        // calculate the last hash and put it into the last position
        $belegData[self::BELEG_INDEX][Beleg::SIG_VORIGER_BELEG] = BelegService::generateChainingValue(
            $belegData[self::PREVBELEGJWS_INDEX],
            $signature,
            $belegData[self::BELEG_INDEX][Beleg::KASSEN_ID]
        );

        $beleg = BelegService::createNewBeleg($belegData[self::BELEG_INDEX]);

        $belegDecorator = BelegService::decorateBeleg($beleg, $signature, $belegData[self::TYP_INDEX]);
        $belegDecorator->setToPrepared();

        return $belegDecorator;
    }

    /**
     * @param string $encryptedString
     * @return array
     */
    public static function extractSignedParts($encryptedString) {
        $parts = explode('.',$encryptedString);

        return [
            self::JWS_HEAD_INDEX    => $parts[0],
            self::JWS_INDEX         => $parts[1],
            self::JWS_SIGNATURE     => $parts[2]
        ];
    }
}