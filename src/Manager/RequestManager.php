<?php
namespace MED\Kassa\Manager;

use MED\Kassa\Decorator\BelegDecorator;
use MED\Kassa\Model\RequestConfig;
use MED\Kassa\Model\RestConfig;
use MED\Kassa\Service\BelegService;
use MED\Kassa\Service\RESTService;

/**
 * Class BelegManager
 * @package MED\Kassa\Manager
 */
class RequestManager
{
    /**
     * @var RESTService
     */
    private $restService;

    /**
     * @var RestConfig
     */
    private $restConfig;

    /**
     * @var RequestConfig
     */
    private $requestConfig;

    /**
     * @var BelegDecorator
     */
    private $belegDecorator;


    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getSignedJWS() {

        $this->requestConfig->setPayload($this->getBelegDecorator()->getJWS());
        $promise = $this->restService->sendSigningRequest($this->restConfig, $this->requestConfig);

        /**
         * @var \GuzzleHttp\Psr7\Response $response
         */
        $response = $promise->wait();
        $signedPayload = json_decode($response->getBody()->getContents(), true);



        if (isset($signedPayload['result'])) {
            $parts = BelegService::extractSignedParts($signedPayload['result']);
            $this->belegDecorator->setSignedJWS($parts[BelegService::JWS_SIGNATURE]);
        }

        return $signedPayload['result'];
    }

    /**
     * @return RESTService
     */
    public function getRestService()
    {
        return $this->restService;
    }

    /**
     * @param RESTService $restService
     * @return self
     */
    public function setRestService(RESTService $restService)
    {
        $this->restService = $restService;
        return $this;
    }

    /**
     * @return RestConfig
     */
    public function getRestConfig()
    {
        return $this->restConfig;
    }

    /**
     * @param RestConfig $restConfig
     * @return self
     */
    public function setRestConfig(RestConfig $restConfig)
    {
        $this->restConfig = $restConfig;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestConfig()
    {
        return $this->requestConfig;
    }

    /**
     * @param RequestConfig $requestConfig
     * @return self
     */
    public function setRequestConfig(RequestConfig $requestConfig)
    {
        $this->requestConfig = $requestConfig;
        return $this;
    }

    /**
     * @return BelegDecorator
     */
    public function getBelegDecorator()
    {
        return $this->belegDecorator;
    }

    /**
     * @param BelegDecorator $belegDecorator
     * @return self
     */
    public function setBelegDecorator(BelegDecorator $belegDecorator)
    {
        $this->belegDecorator = $belegDecorator;
        return $this;
    }
}