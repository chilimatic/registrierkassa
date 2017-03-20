<?php
namespace MED\Kassa\Service;

use chilimatic\lib\Di\ClosureFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as HTTPRequest;
use MED\Kassa\Model\RestConfig;
use MED\Kassa\Model\RequestConfig;
use MED\Kassa\Traits\ServiceLocatorTrait;

/**
 * Class RESTService
 * @package MED\Kassa\Service
 */
class RESTService
{
    use ServiceLocatorTrait;

    const SIGN_ACTION = 'sign';
    const ZDA_ACTION = 'zda';
    const CERT_ACTION = 'certificate';

    /**
     * @var array
     */
    private static $actionSet = [
        self::SIGN_ACTION, self::ZDA_ACTION, self::CERT_ACTION
    ];

    /**
     * @var array
     */
    private static $jsonHeader = ['Content-Type' => 'application/json'];

    private static $options = [
        'verify' => false
    ];

    /**
     * @var Client
     */
    private $httpClient;


    /**
     * RESTService constructor.
     * @param ClosureFactory $di
     */
    public function __construct(ClosureFactory $di)
    {
        $this->setDi($di);
        $this->initialize();
    }

    public function initialize() {
        $this->httpClient = $this->getDi()->get('http-client');
    }

    /**
     * @param RestConfig $restConfig
     * @param string $action
     * @param string $user
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function urlBuilder(RestConfig $restConfig, $action, $user)
    {
        if (!is_string($user)) {
            throw new \InvalidArgumentException(__METHOD__ . ' $user hast to a string. Given: ' . print_r($user, true));
        }

        if (!in_array($action, self::$actionSet, true)) {
            throw new \InvalidArgumentException(__METHOD__ . ' has been called with unregistered Action: ' . $action);
        }

        return implode('/', [
            $restConfig->getApiRootUrl(),
            $restConfig->getApiVersion(),
            $user,
            $restConfig->getApiUriMap()[$action]
        ]);
    }

    /**
     * @param RestConfig $restConfig
     * @param RequestConfig $requestConfig
     * @return \GuzzleHttp\Promise\PromiseInterface
     * @throws \InvalidArgumentException
     */
    public function getZDA(RestConfig $restConfig, RequestConfig $requestConfig)
    {
        $url = $this->urlBuilder($restConfig, self::ZDA_ACTION, $requestConfig->getName());

        $httpRequest = new HTTPRequest('GET', $url, self::$jsonHeader);
        return $this->httpClient->sendAsync($httpRequest, self::$options);
    }

    /**
     * @param RestConfig $restConfig
     * @param RequestConfig $requestConfig
     * @return \GuzzleHttp\Promise\PromiseInterface
     * @throws \InvalidArgumentException
     */
    public function sendSigningRequest(RestConfig $restConfig, RequestConfig $requestConfig)
    {
        $url = $this->urlBuilder($restConfig, self::SIGN_ACTION, $requestConfig->getName());
        $payload = json_encode([
            'password' => $requestConfig->getPassword(),
            'jws_payload' => $requestConfig->getPayload()
        ]);

        $httpRequest = new HTTPRequest('POST', $url, self::$jsonHeader, $payload);
        return $this->httpClient->sendAsync($httpRequest, self::$options);
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
}