<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 22/04/18
 * Time: 13:29
 */

namespace HamhamFonfon\Astrobin\Response;
use HamhamFonfon\Astrobin\Exceptions\AstrobinResponseExceptions;

/**
 * Class AbstractAstrobinResponse
 * @package AppBundle\Astrobin\Response
 */
abstract class AbstractAstrobinResponse
{

    /**
     * Convert stdClass from WS to an array
     * @param \stdClass $obj
     * @throws AstrobinResponseExceptions
     * @throws \ReflectionException
     */
    public function fromObj(\stdClass $obj)
    {
        $this->fromArray((array)$obj);
    }


    /**
     * Build properties of class based on WS response
     * @param array $objArr
     * @throws AstrobinResponseExceptions
     * @throws \ReflectionException
     */
    private function fromArray(array $objArr)
    {
        /** @var \ReflectionClass $reflector */
        $reflector = new \ReflectionClass($this);

        foreach ($reflector->getProperties() as $property) {
            if (!array_key_exists($property->getName(), $objArr)) {
                throw new AstrobinResponseExceptions(
                    sprintf("Property \"%s\" doesn't exist in class %s", $property->getName(), get_class($this))
                );
            }

            $property->setValue($this, $objArr[$property->getName()]);
        }
    }


}