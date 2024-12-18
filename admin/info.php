<?php
require_once('vendor/autoload.php');

use GuzzleHttp\Client;

$apiKey = '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'; // Remplacez par votre clé API Brevo
$client = new Client();

$response = $client->request('GET', 'https://api.sendinblue.com/v3/smtp/templates', [
    'headers' => [
        'accept' => 'application/json',
        'api-key' => $apiKey,
    ],
]);

$body = $response->getBody();
echo $body;

?>
