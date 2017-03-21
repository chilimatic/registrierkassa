<?php
namespace MED\Kassa\Builder;

use chilimatic\lib\Di\ClosureFactory;
use MED\Kassa\Decorator\BelegDecorator;
use MED\Kassa\Manager\RequestManager;
use MED\Kassa\Model\RequestConfig;
use MED\Kassa\Model\RestConfig;
use MED\Kassa\Service\RESTService;
use MED\Kassa\Traits\ServiceLocatorTrait;

/**
 * Class BelegManagerBuilder
 * @package MED\Kassa\Builder
 */
class BelegManagerBuilder
{
    use ServiceLocatorTrait;

    /**
     * BelegManagerBuilder constructor.
     * @param ClosureFactory $di
     */
    public function __construct(ClosureFactory $di)
    {
        $this->setDi($di);
    }

    /**
     * @param RestConfig $restConfig
     * @param RequestConfig $requestConfig
     * @param BelegDecorator $belegDecorator
     *
     * @return RequestManager
     */
    public function buildManager(RestConfig $restConfig, RequestConfig $requestConfig, BelegDecorator $belegDecorator)
    {
        $belegManager = new RequestManager($this->getDi());
        $belegManager->setRestConfig($restConfig);
        $belegManager->setRequestConfig($requestConfig);
        $belegManager->setRestService(new RESTService($this->getDi()));
        $belegManager->setBelegDecorator($belegDecorator);

        return $belegManager;
    }
}