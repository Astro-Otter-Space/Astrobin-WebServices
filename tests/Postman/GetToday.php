<?php

$curl = initCurl('imageoftheday/', 'GET', ['limit' => 1]);
if(!$resp = curl_exec($curl)) {
    echo "\nEmpty";
}
curl_close($curl);
$obj = json_decode($resp);
print_r($obj);

$today = new \DateTime('now');
print_r($today->format("Y-m-d")."\n");


if (preg_match('/\/([\d]+)/', $obj->objects[0]->image, $matches)) {
    $imageId = $matches[1];
}







function initCurl($endPoint, $method, $data)
{
    $apiKey = '3524e6ee81749ea19a1ed0f14c5390efb4ac578f';
    $apiSecret = '6f0a67f7aeb93cbce4addec000fca9991876df63';

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

    print_r("URL : $url\n");
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


    curl_setopt_array($curl, $options);
    return $curl;
}