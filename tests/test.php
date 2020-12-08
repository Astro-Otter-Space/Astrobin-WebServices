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

# Response
include __DIR__ . './../src/Response/AstrobinResponse.php';
include __DIR__ . './../src/Response/AbstractResponse.php';
include __DIR__ . './../src/Response/ListImages.php';
include __DIR__ . './../src/Response/Image.php';

$imageWs = new \AstrobinWs\Services\GetImage();

$idAlphaNum = 'tiy8v8';
//$response = $imageWs->getById($idAlphaNum);

$idOnlyNum = (string)341955;
//$response = $imageWs->getById($idOnlyNum);

$response = $imageWs->getImagesBySubject('m42', 3);
dump($response);
