<?php
namespace MED\Kassa\Model;

/**
 * Class RequestConfig
 * @package MED\Kassa\Model
 */
class RequestConfig
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $password = '';

    /**
     * @var string
     */
    private $payload = '';

    /**
     * Request constructor.
     * @param string $name
     * @param string $password
     * @param string $payload
     * @throws \InvalidArgumentException
     */
    public function __construct($name = null, $password = null, $payload = null)
    {
        if ($name) {
            $this->setName($name);
        }

        if ($password) {
            $this->setPassword($password);
        }

        if ($payload) {
            $this->setPayload($payload);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string) $this->name;
    }

    /**
     * @param string $name
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setName($name)
    {
        if (!is_string($name) || !$name) {
            throw new \InvalidArgumentException(
                __METHOD__
                . ': $name has to be a string and cannot be empty. Given: '
                . print_r($name, true)
            );
        }

        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setPassword($password)
    {
        if (!is_string($password)) {
            throw new \InvalidArgumentException(
                __METHOD__
                . ': $password has to be a string . Given: '
                . print_r($password, true)
            );
        }
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPayload()
    {
        return (string) $this->payload;
    }

    /**
     * @param string $payload
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setPayload($payload)
    {
        if (!is_string($payload) || !$payload) {
            throw new \InvalidArgumentException(
                __METHOD__
                . ': $payload has to be a string and cannot be empty. Given: '
                . print_r($payload, true)
            );
        }

        $this->payload = $payload;
        return $this;
    }
}