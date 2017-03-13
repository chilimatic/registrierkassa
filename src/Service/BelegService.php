<?php
namespace MED\Kassa\Service;

use MED\Kassa\Decorator\BelegDecorator;
use MED\Kassa\Model\Beleg;

class BelegService {

    /**
     * @param array $data
     *
     * @return Beleg
     * @throws \InvalidArgumentException
     */
    public function createBeleg(array $data)
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
     * @param string $type
     * @return BelegDecorator
     * @throws \InvalidArgumentException
     */
    public function decorateBeleg(Beleg $beleg, $type) {
        return new BelegDecorator($beleg, $type);
    }
}