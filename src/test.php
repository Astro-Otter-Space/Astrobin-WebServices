<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

#Exception
include __DIR__ . '/Exceptions/WsException.php';
include __DIR__ . '/Exceptions/WsResponseException.php';

# WS
include __DIR__ . '/GuzzleSingleton.php';
include __DIR__ . '/AbstractWebService.php';
include __DIR__ . '/Services/WsInterface.php';
include __DIR__ . '/Services/GetImage.php';
//include __DIR__ . '/Services/GetUser.php';

# Response
include __DIR__ . '/Response/DTO/AstrobinResponse.php';
include __DIR__ . '/Response/AbstractResponse.php';
//include __DIR__ . '/Response/DTO/Collection/ListImages.php';
//include __DIR__ . '/Response/DTO/Collection/ListCollection.php';
//include __DIR__ . '/Response/DTO/Collection/ListToday.php';
include __DIR__ . '/Response/DTO/Item/Image.php';
//include __DIR__ . '/Response/DTO/Item/User.php';
//include __DIR__ . '/Response/DTO/Item/Collection.php';

$astrobinApiKey = getenv('ASTROBIN_API_KEY');
$astrobinApiSecret = getenv('ASTROBIN_API_SECRET');

$astrobinWs = new \AstrobinWs\Services\GetImage($astrobinApiKey, $astrobinApiSecret);
try {
    $response = $astrobinWs->getById('n4nth8');
    var_dump($response);
} catch (\AstrobinWs\Exceptions\WsException|JsonException) {
}

