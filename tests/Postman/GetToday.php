<?php
include __DIR__ . './../../src/Exceptions/WsException.php';
include __DIR__ . './../../src/Exceptions/WsResponseException.php';
include __DIR__ . './../../src/AbstractWebService.php';
include __DIR__ . './../../src/WsInterface.php';
include __DIR__ . './../../src/Services/GetImage.php';
include __DIR__ . './../../src/Response/ImageIterator.php';
include __DIR__ . './../../src/Response/ListImages.php';
include __DIR__ . './../../src/Response/AbstractResponse.php';
include __DIR__ . './../../src/Response/Today.php';
include __DIR__ . './../../src/Response/Image.php';

$curl = initCurl('imageoftheday/', 'GET', ['limit' => 1]);
if(!$resp = curl_exec($curl)) {
    echo "\nEmpty";
}

$rawResp = json_decode($resp);

if (property_exists($rawResp, "objects") && property_exists($rawResp, "meta") && 0 < $rawResp->meta->total_count) {
    $objects = $rawResp->objects;

    $astrobinToday = new \Astrobin\Response\Today();
    $astrobinToday->fromObj($objects[0]);

    if (preg_match('/\/([\d]+)/', $astrobinToday->resource_uri, $matches)) {
        $imageId = $matches[1];
        $sndCurl = initCurl('image/', 'GET', $imageId);
        $sndResp = curl_exec($sndCurl);
        $sndobject = json_decode($sndResp);

        $image = new \Astrobin\Response\Image();
        $image->fromObj($sndobject);

        $astrobinToday->add($image);
    }
}
curl_close($curl);

print_r($astrobinToday);
die();

function initCurl($endPoint, $method, $data)
{
    $apiKey = '';
    $apiSecret = '';

    // Build URL with params
    $url = 'https://www.astrobin.com/api/v1/' . $endPoint;
    if (is_array($data) && 0 < count($data)) {
        $paramData = implode('&', array_map(function($k, $v) {
            $formatValue = "%s";
            if (is_numeric($v)) {
                $formatValue = "%d";
            }
            return sprintf("%s=$formatValue", $k, $v);
        }, array_keys($data), $data));

        $url .= '?' . $paramData;
    } else {
        if ('/' !== substr($url, strlen($url)-1, strlen($url))) {
            $url .= '/';
        }
        // Warning :
        $url .= $data . '/?';
    }

    // Add keys and format
    $params = [
        'api_key' => $apiKey,
        'api_secret' => $apiSecret,
        'format' => 'json'
    ];

    $url .= implode('', array_map(function($k, $v) {
        return sprintf("&%s=%s", $k, $v);
    }, array_keys($params), $params));

    $curl = curl_init();

    // Options CURL
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ];
    var_dump($url);
    curl_setopt_array($curl, $options);
    return $curl;
}