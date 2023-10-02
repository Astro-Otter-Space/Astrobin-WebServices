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
include __DIR__ . './../src/Response/DTO/Collection/ListImages.php';
include __DIR__ . './../src/Response/DTO/Collection/ListCollection.php';
include __DIR__ . './../src/Response/DTO/Collection/ListToday.php';
include __DIR__ . './../src/Response/DTO/Item/Image.php';
include __DIR__ . './../src/Response/DTO/Item/User.php';
include __DIR__ . './../src/Response/DTO/Item/Collection.php';

$astrobinApiKey = getenv('ASTROBIN_API_KEY');
$astrobinApiSecret = getenv('ASTROBIN_API_SECRET');

$astrobinWs = new \AstrobinWs\Services\GetCollection($astrobinApiKey, $astrobinApiSecret);
try {
    $response = $astrobinWs->getListCollectionByUser('siovene', 1);
    var_dump($response);
} catch (\AstrobinWs\Exceptions\WsException|JsonException) {
}

