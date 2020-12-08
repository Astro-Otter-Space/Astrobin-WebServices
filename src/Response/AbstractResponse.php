<?php

declare(strict_types=1);

namespace AstrobinWs\Response;

use AstrobinWs\Exceptions\WsResponseException;

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
    public function fromObj(\stdClass $obj): void
    {
        $this->fromArray((array)$obj);
    }


    /**
     * Build properties of class based on WS response
     * @param array $objArr
     * @throws WsResponseException
     * @throws \ReflectionException
     */
    private function fromArray(array $objArr): void
    {
        $listNotFields = ['listImages'];

        /** @var \ReflectionClass $reflector */
        $reflector = new \ReflectionClass($this);
        foreach ($reflector->getProperties() as $property) {
            if (in_array($property->getName(), $listNotFields)) {
                $property->setAccessible(true);
                $property->setValue($this, null);
                continue;
            }
            if (!array_key_exists($property->getName(), $objArr)) {
                throw new WsResponseException(
                    sprintf("Property \"%s\" doesn't exist in class %s", $property->getName(), get_class($this)),
                    500,
                    null
                );
            }

            $property->setValue($this, $objArr[$property->getName()]);
        }
    }
}
