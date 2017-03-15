<?php
use MED\Kassa\Model\Beleg;

trait ProviderTrait
{
    public static $HASH_1 = '61844b82a0c7976e5a0f30d7a0ed9a455338aeac7b08ced11549f9f202158b90';
    public static $HASH_2 = 'eabc0f3dcf7880971779de7cb741c48ac9d4962707a303baa024b1207942e2fb';
    public static $HASH_3 = 'fffd88db425b091b24b4b9970e17d7c328378ada31eb9b43ff9ec3dca316aeae';
    public static $HASH_4 = '20e3bdcdebcfd6a825e332b46b2906a6bedc3c1afcf81e497ed8bb7b572f88af';

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
     * @return array
     */
    public function hashBaseProvider() {
        return [
            [
                'DEMO-CASH-BOX703' . '496410', 'sha256', self::$HASH_1
            ],
            [
                'DEMO-CASH-BOX555' . 'A123ffa', 'sha256', self::$HASH_2
            ],
            [
                'DEMO-CASH-BOXDFs' . 'm234Afs', 'sha256', self::$HASH_3
            ],
            [
                '1231123' . '636363', 'sha256', self::$HASH_4
            ],
        ];
    }

    /**
     * @return array
     */
    public function hashProvider() {
        return [
            [self::$HASH_1, 8, 'YYRLgqDHl25a'],
            [self::$HASH_2, 12, '6rwPPc94gJcXed58t0HEisnU'],
            [self::$HASH_3, 24, '//2I20JbCRsktLmXDhfXwyg3itox65tD/57D3KMW'],
            [self::$HASH_4, 8, 'IOO9zevP1qgl4zK0ayk=']
        ];
    }


    /**
     * reciever($belegData, $signaturePosIndex, $base64Signature)
     *
     * @return array
     */
    public function belegDataProviderWithSignature() {
        return [
            [
                [ // belegdata
                    Beleg::KASSEN_ID => '1',
                    Beleg::BELEG_NUMMER => '000001',
                    Beleg::BELEG_DATUM_UHRZEIT => '2017-03-03'. 'T' . '23:56:22',
                    Beleg::BETRAG_SATZ_NORMAL => 0,
                    Beleg::BETRAG_SATZ_NULL => 0,
                    Beleg::BETRAG_SATZ_ERMAESSIGT_1 => 0,
                    Beleg::BETRAG_SATZ_ERMAESSIGT_2 => 0,
                    Beleg::BETRAG_SATZ_BESONDERS => 0,
                    Beleg::ZERTIFIKAT_SERIENNUMMER => 0,
                    Beleg::SIG_VORIGER_BELEG => '1',
                    Beleg::STAND_UMSATZZAEHLER => 0
                ],
                1, // signature index
                'eyJhbGciOiJFUzI1NiJ9X1IxLUFUMV8xXzAwMDAwMV8yMDE3LTAzLTAzVDIzOjU2OjIyXzAsMDBfMCwwMF8wLDAwXzAsMDBfMCwwMF8wXzBfMQ' // base64 signature
            ],
            [
                [ // belegdata
                    Beleg::KASSEN_ID => '1',
                    Beleg::BELEG_NUMMER => '000002',
                    Beleg::BELEG_DATUM_UHRZEIT => '2017-03-05'. 'T' . '07:12:12',
                    Beleg::BETRAG_SATZ_NORMAL => 10,
                    Beleg::BETRAG_SATZ_NULL => 0,
                    Beleg::BETRAG_SATZ_ERMAESSIGT_1 => 0,
                    Beleg::BETRAG_SATZ_ERMAESSIGT_2 => 0,
                    Beleg::BETRAG_SATZ_BESONDERS => 0,
                    Beleg::ZERTIFIKAT_SERIENNUMMER => 0,
                    Beleg::SIG_VORIGER_BELEG => 'eyJhbGciOiJFUzI1NiJ9X1IxLUFUMV8xX18yMDE3LTAzLTAzVDIzOjU2OjIyXzAsMDBfMCwwMF8wLDAwXzAsMDBfMCwwMF8wXzBfMQ',
                    Beleg::STAND_UMSATZZAEHLER => 1000
                ],
                1, // signature index
                'eyJhbGciOiJFUzI1NiJ9X1IxLUFUMV8xXzAwMDAwMl8yMDE3LTAzLTA1VDA3OjEyOjEyXzEwLDAwXzAsMDBfMCwwMF8wLDAwXzAsMDBfMTAwMF8wX2V5SmhiR2NpT2lKRlV6STFOaUo5WDFJeExVRlVNVjh4WDE4eU1ERTNMVEF6TFRBelZESXpPalUyT2pJeVh6QXNNREJmTUN3d01GOHdMREF3WHpBc01EQmZNQ3d3TUY4d1h6QmZNUQ' // base64 signature
            ]
        ];
    }


    /**
     * @return array
     */
    public function dataProviderBelegToString() {
        return [
            [
                [
                    'DEMO-CASH-BOX524',
                    '366585AB',
                    '2015-12-17T11:23:43',
                    '0.00',
                    '0.00',
                    '0.00',
                    '0.00',
                    '0.00',
                    '5/4fWv5/uhI=',
                    '245abcde',
                    'lDUkNhEeJKY='
                ],
                '_DEMO-CASH-BOX524_366585AB_2015-12-17T11:23:43_0,00_0,00_0,00_0,00_0,00_5/4fWv5/uhI=_245abcde_lDUkNhEeJKY=',
                \MED\Kassa\Model\Signature::A_TRUST_POS,
                '_R1-AT1'
            ],
            [
                [
                    'DEMO-CASH-BOX123',
                    '366585AC',
                    '2015-12-17T11:23:43',
                    '0.00',
                    '0.00',
                    '0.00',
                    '0.00',
                    '0.00',
                    '5/4fWv5/uhI=',
                    '245abcde',
                    'lDUkNhEeJKY='
                ],
                '_DEMO-CASH-BOX123_366585AC_2015-12-17T11:23:43_0,00_0,00_0,00_0,00_0,00_5/4fWv5/uhI=_245abcde_lDUkNhEeJKY=',
                0,
                '_R1-AT0'
            ],
            [
                [
                    'DEMO-CASH-BOX678',
                    '366585AD',
                    '2015-12-17T11:23:43',
                    '0.00',
                    '0.00',
                    '0.00',
                    '0.00',
                    '0.00',
                    '5/4fWv5/uhI=',
                    '245abcde',
                    'lDUkNhEeJKY='
                ],
                '_DEMO-CASH-BOX678_366585AD_2015-12-17T11:23:43_0,00_0,00_0,00_0,00_0,00_5/4fWv5/uhI=_245abcde_lDUkNhEeJKY=',
                2,
                '_R1-AT2'
            ]
        ];
    }
}