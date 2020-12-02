<?php

use AstrobinWs\Services\GetImage;
use PHPUnit\Framework\TestCase;

/**
 * Class GetImageTest
 */
class GetImageTest extends TestCase
{
    public $astrobinImageMock;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        /** @var \PHPUnit\Framework\MockObject\MockObject astrobinImageMock */
        $this->astrobinImageMock = $this->getMockBuilder(GetImage::class)
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();
    }


    /**
     * Test by ID OK
     */
    public function testGetImageById()
    {
        $id = 341955;
        $response = $this->client->getImageById($id);
        $this->astrobinImageMock->

        $this->assertInstanceOf(\AstrobinWs\Response\Image::class, $response, __METHOD__ . ' : response Image OK');
        $this->assertClassHasAttribute('title', \AstrobinWs\Response\Image::class, __METHOD__ . ': attribute title OK');
    }


    /**
     * Test with null Id
     * @expectedException \AstrobinWs\Exceptions\WsResponseException
     */
    public function testGetImageWithNullId()
    {
        $this->setExpectedExceptionFromAnnotation();
        $this->client->getImageById(null);
        $this->expectExceptionMessage("[Astrobin response] '' is not a correct value, integer expected");
    }


    /**
     * Test bith Bad Id
     *
     * @expectedException \AstrobinWs\Exceptions\WsResponseException
     */
    public function testGetImageWithBadId()
    {
        $this->setExpectedExceptionFromAnnotation();
        $fakeId = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(20/strlen($x)) )),1,20);
        $this->client->getImageById($fakeId);
        $this->expectExceptionMessage("[Astrobin response] \'$fakeId\' is not a correct value, integer expected");
    }


    /**
     * Test by subjects
     */
    public function testGetImagesBySubject()
    {
        $subjects = ['m1', 'andromeda', 'm33', 'm42', 'pleiades', 'm51', 'm57', 'm82', 'm97', 'm101', 'm104'];
        foreach ($subjects as $subject) {
            $limit = rand(1, 5);
            $response = $this->client->getImagesBySubject($subject, $limit);

            $instance = ($limit > 1) ? \AstrobinWs\Response\ListImages::class : \AstrobinWs\Response\Image::class;
            $this->assertInstanceOf($instance, $response, __METHOD__  . " : response $instance OK");
            if (is_a($response, \AstrobinWs\Response\ListImages::class)) {
                // Test images
                foreach ($response->getIterator() as $respImage) {
                    $this->assertInstanceOf(\AstrobinWs\Response\Image::class, $respImage, __METHOD__ . ' : check if instance Image OK');
                }

            } else if (is_a($response, \AstrobinWs\Response\Image::class)) {
                $this->assertInstanceOf(\AstrobinWs\Response\Image::class, $response, __METHOD__ . ' : check if instance Image OK');
            }
        }
    }


    /**
     * @expectedException \AstrobinWs\Exceptions\WsResponseException
     */
    public function testGetImagesBySubjectNotFound()
    {
        $this->setExpectedExceptionFromAnnotation();
        $fakeSubject = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(20/strlen($x)) )),1,20);
        $this->client->getImagesBySubject($fakeSubject, rand(1, 5));
        $this->expectException(\AstrobinWs\Exceptions\WsResponseException::class);
    }


    /**
     * Test by username
     */
    public function testGetImageByUser()
    {
        $listUser = ['gorann', 'protoplot', 'tlewis', 'Mark_Hudson', 'SparkyHT'];
        $user = $listUser[array_rand($listUser, 1)];
        $response = $this->client->getImagesByUser($user, 5);

        $this->assertInstanceOf(\AstrobinWs\Response\ListImages::class, $response, __METHOD__ . ' : ListImages returned OK');
        foreach ($response->getIterator() as $resp) {
            $this->assertEquals($user, $resp->user, __METHOD__ . ' : ' . $user . ' selected is equale to ' . $resp->user);
        }
    }


    /**
     * Test with fake user
     * @expectedException \AstrobinWs\Exceptions\WsResponseException
     */
    public function testGetImagesByBadUser()
    {
        $this->setExpectedExceptionFromAnnotation();
        $fakeUser = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(20/strlen($x)) )),1,20);
        $this->client->getImagesByUser($fakeUser, 5);
        $this->expectException(\AstrobinWs\Exceptions\WsResponseException::class);
    }



    /**
     * Test with a range date : now to now-1month
     */
    public function testGetImagesByRangeDate()
    {
        $dateTo = new DateTime('now');
        $dateFrom = clone $dateTo;
        $dateFrom->sub(new DateInterval('P1M'));

        $this->assertRegExp('/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/', $dateFrom->format('Y-m-d'), 'Format dateFrom OK');
        $this->assertRegExp('/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/', $dateTo->format('Y-m-d'), 'Format dateFrom OK');

        $response = $this->client->getImagesByRangeDate($dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d'));
        $this->assertInstanceOf(\AstrobinWs\Response\ListImages::class, $response);

        $dateToTmp = $dateTo->getTimestamp();
        $dateFromTmp = $dateFrom->getTimestamp();

        /** @var \AstrobinWs\Response\Image $imgResp */
        foreach ($response->getIterator() as $imgResp) {
            $timestamp = $imgResp->getUploaded()->getTimestamp();
            $this->assertLessThanOrEqual($dateToTmp, $timestamp, __METHOD__ . ' : interval lether date uploaded OK');
            $this->assertGreaterThanOrEqual($dateFromTmp, $timestamp,__METHOD__ . ' : interval greather date uploaded OK');
        }
    }


    /**
     * @expectedException \AstrobinWs\Exceptions\WsException
     */
    public function testGetImagesByRangeDateFalse()
    {
        $this->setExpectedExceptionFromAnnotation();
        $dateTo = new DateTime('now');
        $dateFrom = clone $dateTo;
        $dateFrom->sub(new DateInterval('P1M'));

        // Test with format yy-mm-dd also yyyy-mm-dd
        $this->client->getImagesByRangeDate($dateFrom->format('y-m-d'), $dateTo->format('Y-m-d'));
        $this->expectException(\AstrobinWs\Exceptions\WsException::class);
    }


    /**
     * @expectedException \AstrobinWs\Exceptions\WsException
     */
    public function testGetImagesByRangeDateBadFormat()
    {
        $this->setExpectedExceptionFromAnnotation();
        $dateTo = new DateTime('now');

        $this->client->getImagesByRangeDate('aabbccddeeff', $dateTo->format('Y-m-d'));
    }
}
