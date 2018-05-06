<?php
//
//use Astrobin\AbstractWebService;
//
//class AbstractWebServiceTest extends \PHPUnit\Framework\TestCase
//{
//
//    protected static $abstractWebServiceClass = 'Astrobin\AbstractWebService';
//
//    protected $badClient;
//
//
//    public function setUp()
//    {
//        parent::setUp();
//        $constructArgs = [null, null];
//        $this->badClient = $this->getMockBuilder(self::$abstractWebServiceClass)->setConstructorArgs([$constructArgs]);
//    }
//
//
//    /**
//     * @expectedException \Astrobin\Exceptions\WsException
//     */
//    public function testNullCredentials()
//    {
//        $this->badClient->call('my_false_endpoint/', AbstractWebService::METHOD_GET, []);
//    }
//}
