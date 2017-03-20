<?php
namespace MED\Kassa\Model;

/**
 * Class RestConfigModel
 * @package MED\Kassa\Model
 */
class RestConfig
{
    /**
     * @var string
     */
    private $apiRootUrl;

    /**
     * @var string
     */
    private $apiVersion;

    /**
     * @var array
     */
    private $apiUriMap;

    /**
     * RestConfig constructor.
     * @param string $apiRootUrl
     * @param string $apiVersion
     * @param array $apiUriMap
     * @throws \InvalidArgumentException
     */
    public function __construct($apiRootUrl = null, $apiVersion = null, array $apiUriMap = null)
    {
        if ($apiRootUrl) {
            $this->setApiRootUrl($apiRootUrl);
        }

        if ($apiVersion) {
            $this->setApiVersion($apiVersion);
        }

        if ($apiUriMap) {
            $this->setApiUriMap($apiUriMap);
        }
    }

    /**
     * @return string
     */
    public function getApiRootUrl()
    {
        return (string) $this->apiRootUrl;
    }

    /**
     * @param string $apiRootUrl
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setApiRootUrl($apiRootUrl)
    {
        if (!is_string($apiRootUrl)) {
            throw new \InvalidArgumentException(__METHOD__ . ': $apiRootUrl has to be a string');
        }

        $this->apiRootUrl = $apiRootUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return (string) $this->apiVersion;
    }

    /**
     * @param string $apiVersion
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setApiVersion($apiVersion)
    {
        if (!is_string($apiVersion)) {
            throw new \InvalidArgumentException(__METHOD__ . ': $apiVersion has to be a string');
        }

        $this->apiVersion = $apiVersion;
        return $this;
    }

    /**
     * @return array
     */
    public function getApiUriMap()
    {
        return (array) $this->apiUriMap;
    }

    /**
     * @param array $apiUriMap
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setApiUriMap(array $apiUriMap)
    {
        $this->apiUriMap = $apiUriMap;
        return $this;
    }
}