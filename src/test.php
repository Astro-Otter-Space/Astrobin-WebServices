<?php

declare(strict_types=1);

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

echo '<pre>';

$astrobinApiKey = getenv('ASTROBIN_API_KEY');
$astrobinApiSecret = getenv('ASTROBIN_API_SECRET');

$astrobinWs = new \AstrobinWs\Services\GetCollection($astrobinApiKey, $astrobinApiSecret);
$response = $astrobinWs->getById('2');

var_dump($response);
//var_dump($response->listToday);
//while($response->getIterator()->valid()) {
//    /** @var Today $today */
//    $today = $response->getIterator()->current();
//    var_dump($today->date);
//    $response->getIterator()->next();
//}