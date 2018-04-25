<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 20/04/18
 * Time: 18:22
 */

namespace HamhamFonfon\Astrobin;

/**
 * Interface astrobinInterface
 * @package AppBundle\Astrobin
 */
interface WsInterface
{
    public function callWs();
    public function responseWs();
}