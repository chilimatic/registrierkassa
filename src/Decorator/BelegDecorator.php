<?php
namespace MED\Kassa\Decorator;

use MED\Kassa\Model\Beleg;
use MED\Kassa\Model\Signature;

/**
 * Class BelegDecorator
 * @package MED\Kassa\Model
 */
class BelegDecorator
{
    const START_BELEG = 1;
    const NORMAL_BELEG = 2;
    const NULL_BELEG = 3;
    const STORNO_BELEG = 4;
    const TRAININGS_BELEG = 5;


    /**
     * set for validation
     * @var array
     */
    private static $VALID_BELEG_SET = [
        self::TRAININGS_BELEG,
        self::NULL_BELEG,
        self::NORMAL_BELEG,
        self::STORNO_BELEG,
        self::START_BELEG
    ];

    /**
     * @var Signature
     */
    private $signature;

    /**
     * @var int
     */
    private $typ;

    /**
     * @var Beleg
     */
    private $beleg;

    /**
     * defines if the Beleg inside will be encrypted / changed in the service
     * @var bool
     */
    private $prepared = false;

    /**
     * @var string
     */
    private $signedJWS;

    /**
     * BelegTyp constructor.
     * @param Beleg $beleg
     * @param Signature $signature
     * @param int $type
     * @throws \InvalidArgumentException
     */
    final public function __construct(Beleg $beleg, Signature $signature, $type = self::NORMAL_BELEG)
    {
        $this->beleg = $beleg;
        $this->signature = $signature;
        $this->setTyp($type);
    }

    /**
     * @return Beleg
     */
    public function getBeleg()
    {
        return $this->beleg;
    }

    /**
     * @return string
     */
    final public function __toString()
    {
        try {
            return  $this->getJWS();
        } catch (\InvalidArgumentException $e) {
            return '';
        }
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    final public function getJWS() {
        $prefix = '_' . $this->signature->getSignaturePrefix();
        return  $prefix . $this->getBeleg()->getJWS();
    }

    /**
     * @return string
     */
    public function getTyp()
    {
        return $this->typ;
    }

    /**
     * @param string $typ
     * @throws \InvalidArgumentException
     */
    final public function setTyp($typ)
    {
        if (!in_array($typ, self::$VALID_BELEG_SET, true)) {
            throw new \InvalidArgumentException(__METHOD__ . ' type has to be valid please check the constants in the class');
        }

        $this->typ = $typ;
    }

    /**
     * @return bool
     */
    public function isPrepared()
    {
        return (bool) $this->prepared;
    }

    /**
     * this can only be triggered once -> once it's prepared this
     * state should not changed back
     */
    final public function setToPrepared()
    {
        $this->prepared = true;
    }

    /**
     * @return Signature
     */
    final public function getSignature() {
        return $this->signature;
    }

    /**
     * @return string
     */
    public function getSignedJWS()
    {
        return $this->signedJWS;
    }

    /**
     * @param string $signedJWS
     * @return self
     */
    public function setSignedJWS($signedJWS)
    {
        $this->signedJWS = $signedJWS;
        return $this;
    }
}