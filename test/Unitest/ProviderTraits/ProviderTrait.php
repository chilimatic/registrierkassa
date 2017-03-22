<?php
use MED\Kassa\Decorator\BelegDecorator;
use MED\Kassa\Model\Beleg;
use MED\Kassa\Service\BelegService;

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
            [self::$HASH_1, 8, 'YYRLgqDHl24='],
            [self::$HASH_2, 12, '6rwPPc94gJcXed58'],
            [self::$HASH_3, 24, '//2I20JbCRsktLmXDhfXwyg3itox65tD'],
            [self::$HASH_4, 8, 'IOO9zevP1qg=']
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

    /**
     * @return array
     */
    public function buildDataProvider() {
        return [
            [
                [
                    BelegService::TYP_INDEX => BelegDecorator::START_BELEG,
                    BelegService::BELEG_INDEX => [
                        Beleg::KASSEN_ID                => 'MES-1-1',
                        Beleg::BELEG_NUMMER             => '1',
                        Beleg::ZERTIFIKAT_SERIENNUMMER  => 'a5251',
                        Beleg::BELEG_DATUM_UHRZEIT      => '2017-04-01T00:00:00',
                        Beleg::BETRAG_SATZ_NORMAL       => 0.0,
                        Beleg::BETRAG_SATZ_ERMAESSIGT_1 => 0.0,
                        Beleg::BETRAG_SATZ_ERMAESSIGT_2 => 0.0,
                        Beleg::BETRAG_SATZ_NULL         => 0.0
                    ],
                    BelegService::PREVBELEGJWS_INDEX => ''
                ],
                1,
                '_R1-AT1_MES-1-1_1_2017-04-01T00:00:00_0,00_0,00_0,00_0,00_0,00_MA==_a5251_56GElc+aovs='
            ],
            [
                [
                    BelegService::TYP_INDEX => BelegDecorator::NORMAL_BELEG,
                    BelegService::BELEG_INDEX => [
                        Beleg::KASSEN_ID                => 'MES-1-1',
                        Beleg::BELEG_NUMMER             => '2',
                        Beleg::ZERTIFIKAT_SERIENNUMMER  => 'a5251',
                        Beleg::BELEG_DATUM_UHRZEIT      => '2017-04-01T08:30:00',
                        Beleg::BETRAG_SATZ_NORMAL       => 12.0,
                        Beleg::BETRAG_SATZ_ERMAESSIGT_1 => 0.0,
                        Beleg::BETRAG_SATZ_ERMAESSIGT_2 => 0.0,
                        Beleg::BETRAG_SATZ_NULL         => 0.0,
                        Beleg::STAND_UMSATZZAEHLER      => 1200
                    ],
                    BelegService::PREVBELEGJWS_INDEX => ''
                ],
                1,
                '_R1-AT1_MES-1-1_2_2017-04-01T08:30:00_12,00_0,00_0,00_0,00_0,00_MTAwMTAxMTAwMDA=_a5251_56GElc+aovs='
            ],
            [
                [
                    BelegService::TYP_INDEX => BelegDecorator::STORNO_BELEG,
                    BelegService::BELEG_INDEX => [
                        Beleg::KASSEN_ID                => 'MES-1-1',
                        Beleg::BELEG_NUMMER             => '3',
                        Beleg::ZERTIFIKAT_SERIENNUMMER  => 'a5251',
                        Beleg::BELEG_DATUM_UHRZEIT      => '2017-04-01T08:30:00',
                        Beleg::BETRAG_SATZ_NORMAL       => 0.0,
                        Beleg::BETRAG_SATZ_ERMAESSIGT_1 => 0.0,
                        Beleg::BETRAG_SATZ_ERMAESSIGT_2 => 0.0,
                        Beleg::BETRAG_SATZ_NULL         => -12.0
                    ],
                    BelegService::PREVBELEGJWS_INDEX => ''
                ],
                1,
                '_R1-AT1_MES-1-1_3_2017-04-01T08:30:00_0,00_0,00_0,00_-12,00_0,00_U1RP_a5251_56GElc+aovs='
            ]
        ];
    }

    /**
     * @return array
     */
    public function certificateDataProvider() {
        return [
            [
                '_R1-AT100_CASHBOX-DEMO-1_CASHBOX-DEMO-1-Receipt-ID-82_2016-03-11T04:24:46_0,00_0,00_0,00_0,00_0,00_NLoiSHL3bsM=_eee257579b03302f_cg8hNU5ihto=',
                'eyJhbGciOiJFUzI1NiJ9.X1IxLUFUMTAwX0NBU0hCT1gtREVNTy0xX0NBU0hCT1gtREVNTy0xLVJlY2VpcHQtSUQtODJfMjAxNi0wMy0xMVQwNDoyNDo0Nl8wLDAwXzAsMDBfMCwwMF8wLDAwXzAsMDBfTkxvaVNITDNic009X2VlZTI1NzU3OWIwMzMwMmZfY2c4aE5VNWlodG89.',
            ],
            [
                '_R1-AT100_CASHBOX-DEMO-2_CASHBOX-DEMO-1-Receipt-ID-100_2017-03-11T04:24:46_0,00_0,00_12,00_0,00_0,00_NLoiSHL3bsM=_eee257579b03302f_cg8hNU5ihto=',
                'eyJhbGciOiJFUzI1NiJ9.X1IxLUFUMTAwX0NBU0hCT1gtREVNTy0yX0NBU0hCT1gtREVNTy0xLVJlY2VpcHQtSUQtMTAwXzIwMTctMDMtMTFUMDQ6MjQ6NDZfMCwwMF8wLDAwXzEyLDAwXzAsMDBfMCwwMF9OTG9pU0hMM2JzTT1fZWVlMjU3NTc5YjAzMzAyZl9jZzhoTlU1aWh0bz0.'
            ],
            [
                '_R1-AT100_CASHBOX-DEMO-2_CASHBOX-DEMO-1-Receipt-ID-124_2017-03-11T04:24:46_0,00_0,00_12,00_0,00_0,00_NLoiSHL3bsM=_eee257579b03302f_cg8hNU5ihto=',
                'eyJhbGciOiJFUzI1NiJ9.X1IxLUFUMTAwX0NBU0hCT1gtREVNTy0yX0NBU0hCT1gtREVNTy0xLVJlY2VpcHQtSUQtMTI0XzIwMTctMDMtMTFUMDQ6MjQ6NDZfMCwwMF8wLDAwXzEyLDAwXzAsMDBfMCwwMF9OTG9pU0hMM2JzTT1fZWVlMjU3NTc5YjAzMzAyZl9jZzhoTlU1aWh0bz0.'
            ]
        ];
    }
}