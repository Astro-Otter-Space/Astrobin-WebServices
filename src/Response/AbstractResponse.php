<?php

namespace Astrobin\Response;
use Astrobin\Exceptions\WsResponseException;

/**
 * Class AbstractResponse
 * @package Astrobin\Response
 */
abstract class AbstractResponse
{

    /**
     * Convert stdClass from WS to an array
     * @param \stdClass $obj
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    public function fromObj(\stdClass $obj)
    {
        $this->fromArray((array)$obj);
    }


    /**
     * Build properties of class based on WS response
     * @param array $objArr
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    private function fromArray(array $objArr)
    {
        /** @var \ReflectionClass $reflector */
        $reflector = new \ReflectionClass($this);

        foreach ($reflector->getProperties() as $property) {
            if (!array_key_exists($property->getName(), $objArr)) {
                throw new WsResponseException(
                    sprintf("Property \"%s\" doesn't exist in class %s", $property->getName(), get_class($this))
                );
            }

            $property->setValue($this, $objArr[$property->getName()]);
        }
    }


}