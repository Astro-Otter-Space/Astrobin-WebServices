<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 05/05/18
 * Time: 14:35
 */

use Astrobin\AbstractWebService;

class AbstractWebServiceTest extends \PHPUnit\Framework\TestCase
{

    protected $badClient;

    public function setUp()
    {
        parent::setUp();

        $abstractWebServiceClass = 'Astrobin\AbstractWebService';
        $constructArgs = [null, null];
        $this->badClient = $this->getMockBuilder($abstractWebServiceClass)->setConstructorArgs([$constructArgs]);
    }

    public function testNullCredentials()
    {

    }

}
