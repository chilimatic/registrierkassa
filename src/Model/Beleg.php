<?php
namespace MED\Kassa\Model;

/**
 * Class Beleg
 * @package MED\Kassa
 */
class Beleg
{
    /**
     * konstanten um tippfehler zu vermeiden von array zu objekt konvertierung
     */
    const KASSEN_ID = 'kassenId';
    const BELEG_NUMMER = 'belegNummer';
    const BELEG_DATUM_UHRZEIT = 'belegDatumUhrzeit';
    const BETRAG_SATZ_NORMAL = 'betragSatzNormal';
    const BETRAG_SATZ_ERMAESSIGT_1 = 'betragSatzErmaessigt1';
    const BETRAG_SATZ_ERMAESSIGT_2 = 'betragSatzErmaessigt2';
    const BETRAG_SATZ_NULL = 'betragSatzNull';
    const BETRAG_SATZ_BESONDERS = 'betragSatzBesonders';
    const STAND_UMSATZZAEHLER = 'standUmsatzZaehlerAES256ICM';
    const ZERTIFIKAT_SERIENNUMMER = 'zertifikatSerienNummer';
    const SIG_VORIGER_BELEG = 'sigVorherigerBeleg';


    /**
     * @var string
     */
    private $kassenId;

    /**
     * @var string
     */
    private $belegNummer;

    /**
     * @var string
     */
    private $belegDatumUhrzeit;

    /**
     * @var float
     */
    private $betragSatzNormal = 0.0;

    /**
     * @var float
     */
    private $betragSatzErmaessigt1 = 0.0;

    /**
     * @var float
     */
    private $betragSatzErmaessigt2 = 0.0;

    /**
     * @var float
     */
    private $betragSatzNull = 0.0;

    /**
     * @var float
     */
    private $betragSatzBesonders = 0.0;

    /**
     * @var string
     */
    private $standUmsatzZaehlerAES256ICM;

    /**
     * @var string
     */
    private $zertifikatSerienNummer;

    /**
     * @var string
     */
    private $sigVorherigerBeleg;

    /**
     * will always throw an exception
     * @param $name
     * @throws \BadMethodCallException
     */
    public function __get($name)
    {
        throw new \BadMethodCallException('property overloading prohibited');
    }

    /**
     * will always throw an exception
     * @param string $name
     * @param mixed $value
     * @throws \BadMethodCallException
     */
    public function __set($name, $value)
    {
        throw new \BadMethodCallException('property overloading prohibited');
    }

    /**
     * will always throw an exception
     * @param string $name
     * @throws \BadMethodCallException
     */
    public function __isset($name)
    {
        throw new \BadMethodCallException('property overloading prohibited');
    }


    /**
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->getJWS();
        } catch (\InvalidArgumentException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getJWS()
    {
        return implode('_', [
            $this->kassenId,
            $this->belegNummer,
            $this->belegDatumUhrzeit,
            $this->numberFormat($this->betragSatzNormal),
            $this->numberFormat($this->betragSatzErmaessigt1),
            $this->numberFormat($this->betragSatzErmaessigt2),
            $this->numberFormat($this->betragSatzNull),
            $this->numberFormat($this->betragSatzBesonders),
            $this->standUmsatzZaehlerAES256ICM,
            $this->zertifikatSerienNummer,
            $this->sigVorherigerBeleg
        ]);
    }

    /**
     * @param $value
     * @return string
     * @throws \InvalidArgumentException
     */
    private function numberFormat($value) {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException(__METHOD__ . ' $value has to be a number');
        }
        return number_format($value, 2, ',', '');
    }

    /**
     * @return string
     */
    public function getKassenId()
    {
        return $this->kassenId;
    }

    /**
     * @param string $kassenId
     * @return $this
     */
    public function setKassenId($kassenId)
    {
        $this->kassenId = $kassenId;
        return $this;
    }

    /**
     * @return string
     */
    public function getBelegNummer()
    {
        return $this->belegNummer;
    }

    /**
     * @param string $belegNummer
     * @return $this
     */
    public function setBelegNummer($belegNummer)
    {
        $this->belegNummer = $belegNummer;
        return $this;
    }

    /**
     * @return string
     */
    public function getBelegDatumUhrzeit()
    {
        return $this->belegDatumUhrzeit;
    }

    /**
     * @param string $belegDatumUhrzeit
     * @return $this
     */
    public function setBelegDatumUhrzeit($belegDatumUhrzeit)
    {
        $this->belegDatumUhrzeit = $belegDatumUhrzeit;
        return $this;
    }

    /**
     * @return float
     */
    public function getBetragSatzNormal()
    {
        return $this->betragSatzNormal;
    }

    /**
     * @param float $betragSatzNormal
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setBetragSatzNormal($betragSatzNormal)
    {
        if (!is_numeric($betragSatzNormal)) {
            throw new \InvalidArgumentException(__METHOD__ . ' value has to be a number');
        }

        $this->betragSatzNormal = $betragSatzNormal;
        return $this;
    }

    /**
     * @return float
     */
    public function getBetragSatzErmaessigt1()
    {
        return $this->betragSatzErmaessigt1;
    }

    /**
     * @param float $betragSatzErmaessigt1
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setBetragSatzErmaessigt1($betragSatzErmaessigt1)
    {
        if (!is_numeric($betragSatzErmaessigt1)) {
            throw new \InvalidArgumentException(__METHOD__ . ' value has to be a number');
        }

        $this->betragSatzErmaessigt1 = $betragSatzErmaessigt1;
        return $this;
    }

    /**
     * @return float
     */
    public function getBetragSatzErmaessigt2()
    {
        return $this->betragSatzErmaessigt2;
    }

    /**
     * @param float $betragSatzErmaessigt2
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setBetragSatzErmaessigt2($betragSatzErmaessigt2)
    {
        if (!is_numeric($betragSatzErmaessigt2)) {
            throw new \InvalidArgumentException(__METHOD__ . ' value has to be a number');
        }
        $this->betragSatzErmaessigt2 = $betragSatzErmaessigt2;
        return $this;
    }

    /**
     * @return float
     */
    public function getBetragSatzNull()
    {
        return $this->betragSatzNull;
    }

    /**
     * @param float $betragSatzNull
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setBetragSatzNull($betragSatzNull)
    {
        if (!is_numeric($betragSatzNull)) {
            throw new \InvalidArgumentException(__METHOD__ . ' value has to be a number');
        }

        $this->betragSatzNull = $betragSatzNull;
        return $this;
    }

    /**
     * @return float
     */
    public function getBetragSatzBesonders()
    {
        return $this->betragSatzBesonders;
    }

    /**
     * @param float $betragSatzBesonders
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setBetragSatzBesonders($betragSatzBesonders)
    {
        if (!is_numeric($betragSatzBesonders)) {
            throw new \InvalidArgumentException(__METHOD__ . ' value has to be a number');
        }
        $this->betragSatzBesonders = $betragSatzBesonders;
        return $this;
    }

    /**
     * @return string
     */
    public function getStandUmsatzZaehlerAES256ICM()
    {
        return $this->standUmsatzZaehlerAES256ICM;
    }

    /**
     * @param string $standUmsatzZaehlerAES256ICM
     * @return $this
     */
    public function setStandUmsatzZaehlerAES256ICM($standUmsatzZaehlerAES256ICM)
    {
        $this->standUmsatzZaehlerAES256ICM = $standUmsatzZaehlerAES256ICM;
        return $this;
    }

    /**
     * @return string
     */
    public function getZertifikatSerienNummer()
    {
        return $this->zertifikatSerienNummer;
    }

    /**
     * @param string $zertifikatSerienNummer
     * @return $this
     */
    public function setZertifikatSerienNummer($zertifikatSerienNummer)
    {
        $this->zertifikatSerienNummer = $zertifikatSerienNummer;
        return $this;
    }

    /**
     * @return string
     */
    public function getSigVorherigerBeleg()
    {
        return $this->sigVorherigerBeleg;
    }

    /**
     * @param string $sigVorherigerBeleg
     * @return $this
     */
    public function setSigVorherigerBeleg($sigVorherigerBeleg)
    {
        $this->sigVorherigerBeleg = $sigVorherigerBeleg;
        return $this;
    }
}