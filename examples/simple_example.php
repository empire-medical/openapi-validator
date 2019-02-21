<?php

/**
 * get validator, spec has to be valid! Use https://github.com/wework/speccy for validation
 */
$validator = new \Mmal\OpenapiValidator\Validator(Symfony\Component\Yaml\Yaml::parse(__DIR__.'simple-example-spec.yaml'));

$requestMethod = 'GET';
$requestUrl = '/books';

/**
 * Make request using http client eg. Guzzle
 * $response = $client->request($requestMethod, $requestUrl);
 *
 *
 * and client responded with following data:
 */

$responseData = [
    [
        'name' => 'Horus Rising',
    ],
    [
        'name' => 'Galaxy in Flames',
    ],
];
$responseCode = 200;

//check actual response against spec - validator will figure out which schema to use based on request method and path
$result = $validator->validateBasedOnRequest(
    $requestUrl,
    $requestMethod,
    $responseCode,
    $responseData
);

//OR if You know which schema You want to check against, just provide operation id
$result = $validator->validate(
    'getBooks',
    $responseCode,
    $responseData
);

//then check if there are any errors
if ($result->hasErrors()) {
    echo $result;
} else {
    echo 'All good';
}

