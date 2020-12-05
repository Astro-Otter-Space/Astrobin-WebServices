<?php

namespace AstrobinWs;

/**
 * @deprecated
 * Class CurlHttpRequest
 * @package Astrobin
 */
class CurlHttpRequest implements CurlHttpRequestInterface
{
    private $handle;

    /**
     * CurlHttpRequest constructor.
     */
    public function __construct()
    {
        $this->handle = curl_init();
    }

    /**
     * @return false|resource
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param $name
     * @param $value
     *
     * @return void
     */
    public function setOption(string $name, string $value): void
    {
        curl_setopt($this->handle, $name, $value);
    }

    /**
     * @param array $options
     *
     * @return void
     */
    public function setOptionArray(array $options): void
    {
        curl_setopt_array($this->handle, $options);
    }

    /**
     * @return string|bool
     */
    public function execute(): string
    {
        return curl_exec($this->handle);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getInfo(string $name): mixed
    {
        return curl_getinfo($this->handle, $name);
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return curl_error($this->handle);
    }

    /**
     * @return int
     */
    public function getErrNo(): int
    {
        return curl_errno($this->handle);
    }

    /**
     *
     */
    public function close(): void
    {
        curl_close($this->handle);
    }
}
