<?php
/**
 * Created by PhpStorm.
 * User: stephane
 * Date: 05/05/18
 * Time: 18:04
 */

use Astrobin\Services\GetTodayImage;

class GetTodayImageTest extends PHPUnit_Framework_TestCase
{

    /** @var GetImage */
    private $client;

    const FAKE_KEY = '3524e6ee81749ea19a1ed0f14c5390efb4ac578f';

    const FAKE_SECRET = '6f0a67f7aeb93cbce4addec000fca9991876df63';

    public function setUp()
    {
        parent::setUp();
        $this->client = new GetImage(self::FAKE_KEY, self::FAKE_SECRET);
    }

    public function testGetDayImage()
    {

    }

    public function testGetTodayDayImage()
    {

    }
}
