<?php

declare(strict_types=1);

use AstrobinWs\Filters\ImageFilters;
use AstrobinWs\Response\Image;

require_once dirname(__DIR__) . '/vendor/autoload.php';

#Exception
include __DIR__ . './../src/Exceptions/WsException.php';
include __DIR__ . './../src/Exceptions/WsResponseException.php';

# WS
include __DIR__ . './../src/GuzzleSingleton.php';
include __DIR__ . './../src/AbstractWebService.php';
include __DIR__ . './../src/Services/WsInterface.php';
include __DIR__ . './../src/Services/GetImage.php';
include __DIR__ . './../src/Services/GetUser.php';

# Response
include __DIR__ . './../src/Response/AstrobinResponse.php';
include __DIR__ . './../src/Response/AbstractResponse.php';
include __DIR__ . './../src/Response/ListImages.php';
include __DIR__ . './../src/Response/Image.php';
include __DIR__ . './../src/Response/User.php';

$astrobinApiKey = getenv('ASTROBIN_API_KEY');
$astrobinApiSecret = getenv('ASTROBIN_API_SECRET');

/**
 * IMAGE
 */
$imageWs = new \AstrobinWs\Services\GetImage($astrobinApiKey, $astrobinApiSecret);

//$idAlphaNum = '8p7u7d';
//try {
//    /** @var Image $response */
//    $response = $imageWs->getImageById($idAlphaNum);
//    var_dump($response->url_hd);
//} catch (Exception $e) {
//    var_dump($e->getMessage());
//}

$idOnlyNum = (string)341955;
//$response = $imageWs->getById($idOnlyNum);

$response = $imageWs->getImagesBySubject('m42', 3);
/** @var Image $image */
foreach ($response->getIterator() as $image) {
    var_dump($image->title);
}

$filters = [
    'title__icontains' => 'm42',
    'field_bad' => 'coucou'
];
//$response = $imageWs->getImageBy($filters, 3);
//var_dump($response);

/**
 * TODAY
 */
//$todayWs = new \AstrobinWs\Services\GetTodayImage($astrobinApiKey, $astrobinApiSecret);
//$today = $todayWs->getTodayImage();
//var_dump($today);

//$listDays = $todayWs->getDayImage(1, 2);
//var_dump(iterator_count($listDays));

/**
 * Collection
 */
//$collectionWs = new \AstrobinWs\Services\GetCollection($apiKey, $apiSecret);
//$collection = $collectionWs->getById("655");
//var_dump($collection);

/**
 * User
 */
//$userWs = new \AstrobinWs\Services\GetUser($apiKey, $apiSecret);
//$user = $userWs->getById((string)500);
//var_dump($user);