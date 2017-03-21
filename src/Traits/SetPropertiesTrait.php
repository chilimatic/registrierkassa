<?php
namespace MED\Kassa\Traits;

/**
 * Class SetPropertiesTrait
 * @package MED\Kassa\Traits
 */
trait SetPropertiesTrait
{
    /**
     * @param $obj
     * @param array $data
     * @return mixed
     */
    public static function setProperties($obj, array $data)
    {
        foreach($data as $propertyName => $value) {
            $methodName = 'set' . ucfirst($propertyName);
            if (method_exists($obj, $methodName)) {
                $obj->{$methodName}($value);
            }
        }

        return $obj;
    }
}