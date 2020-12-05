<?php

require './src/AbstractWebService.php';
require './src/Services/GetImage.php';
require './src/Services/WsInterface.php';

$imageWs = new \AstrobinWs\Services\GetImage();
$image = $imageWs->getById(341955);