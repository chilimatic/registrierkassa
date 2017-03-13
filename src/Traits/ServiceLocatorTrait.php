<?php
namespace MED\Kassa\Traits;
use chilimatic\lib\Di\ClosureFactory;

trait ServiceLocatorTrait
{
    private $di;

    /**
     * @return null|ClosureFactory
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * @param mixed $di
     */
    public function setDi(ClosureFactory $di)
    {
        $this->di = $di;
    }
}