<?php

declare(strict_types=1);

use AstrobinWs\Response\DTO\Today;

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
include __DIR__ . './../src/Response/DTO/AstrobinResponse.php';
include __DIR__ . './../src/Response/AbstractResponse.php';
include __DIR__ . './../src/Response/DTO/ListImages.php';
include __DIR__ . './../src/Response/DTO/Image.php';
include __DIR__ . './../src/Response/DTO/User.php';

$astrobinApiKey = getenv('ASTROBIN_API_KEY');
$astrobinApiSecret = getenv('ASTROBIN_API_SECRET');

$imageWs = new \AstrobinWs\Services\GetImage($astrobinApiKey, $astrobinApiSecret);
//$response = $imageWs->getById('8p7u7d');
$response = $imageWs->getImagesByUser('siovene', 3);
echo '<pre>'; var_dump($response);
/**
 * TODAY
 */
//$todayWs = new \AstrobinWs\Services\GetTodayImage($astrobinApiKey, $astrobinApiSecret);
//$limit = random_int(2, 6);
//echo "NB Days : " . $limit . PHP_EOL;
//$interval = $limit-1;
//$now = new \DateTime('now');
//$startDay = clone $now;
//$startDay = $startDay->sub(new \DateInterval(sprintf('P%sD', $limit-1)));
//
//var_dump(sprintf('From %s to %s ',$startDay->format('Y-M-d'), $now->format('Y-m-d')));
//$interval = new \DateInterval('P1D');
//
//$listDates = array_map(static function(\DateTime $date) {
//    return $date->format('Y-m-d');
//}, iterator_to_array((new \DatePeriod($startDay, $interval, $limit-1))->getIterator()));
//echo '<pre>'; print_r($listDates);
//$listDays = $todayWs->getDayImage(0, $limit);
///** @var Today $day */
//foreach ($listDays as $day) {
//    var_dump($day->date);
//}
//
//unset($now, $startDay);

//$listDays = $todayWs->getDayImage(1, 2);
//var_dump(iterator_count($listDays));

/**
 * Collection
 */
//$collectionWs = new \AstrobinWs\Services\GetCollection($astrobinApiKey, $apiSecret);
//$collection = $collectionWs->getById("655");
//var_dump($collection);

///**
// * User
// */
//$userWs = new \AstrobinWs\Services\GetUser($astrobinApiKey, $astrobinApiSecret);
//$user = $userWs->getById('BadId');
//var_dump($user);