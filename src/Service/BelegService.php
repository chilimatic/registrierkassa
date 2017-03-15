<?php
namespace MED\Kassa\Service;

use MED\Kassa\Decorator\BelegDecorator;
use MED\Kassa\Model\Beleg;
use MED\Kassa\Model\Signature;

/**
 * Class BelegService
 * @package MED\Kassa\Service
 */
class BelegService {

    /**
     * @param array $data
     *
     * @return Beleg
     * @throws \InvalidArgumentException
     */
    public function createNewBeleg(array $data)
    {
        if (!$data) {
            throw new \InvalidArgumentException(__METHOD__ . ' $data should not be empty');
        }

        $beleg = new Beleg();
        foreach($data as $propertyName => $value) {
            $methodName = 'set' . ucfirst($propertyName);
            if (method_exists($beleg, $methodName)) {
                $beleg->{$methodName}($value);
            }
        }

        return $beleg;
    }

    /**
     * @param Beleg $beleg
     * @param Signature $signature
     * @param string $type
     * @return BelegDecorator
     *
     * @throws \InvalidArgumentException
     */
    public function decorateBeleg(Beleg $beleg, Signature $signature, $type)
    {
        return new BelegDecorator($beleg, $signature, $type);
    }

    /**
     * @param Signature $signature
     * @return string
     */
    public function getSignatureAlgorithmJsonAsBase64(Signature $signature) {
        return base64_encode(json_encode(['alg' => $signature->getJwsSignatureAlgorithm()]));
    }

    /**
     * @param string $vorherigerBelegAlsJWS
     * @param BelegDecorator $belegDecorator
     * @return string
     * @throws \InvalidArgumentException
     */
    public function generateChainingValue($vorherigerBelegAlsJWS, BelegDecorator $belegDecorator)
    {
        if (!$vorherigerBelegAlsJWS) {
            $hashBase = (string) $belegDecorator->getBeleg()->getKassenId();
        } else {
            $hashBase = (string) $vorherigerBelegAlsJWS;
        }

        $signature = $belegDecorator->getSignature();

        $phpAlgorithm = strtolower(str_replace('-', '', $signature->getJwsSignatureAlgorithm()));

        return CryptoService::extractBytesFromHashAsBase64(
            CryptoService::generateHash($hashBase, $phpAlgorithm),
            $signature->getNumberOfExtractedBytesFromPrevSigHash()
        );
    }
}