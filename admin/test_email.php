<?php
require_once('vendor/autoload.php');

use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;

$apiKey = 'Vxkeysib-3c721e01ad619ef946e22aef231b21279afa338e61030178a598f5ab4e51f4e3-UwgfGPlrGGxnr7b3';
$config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);

$apiInstance = new TransactionalEmailsApi(
    new GuzzleHttp\Client(),
    $config
);

$sendSmtpEmail = new SendSmtpEmail([
    'sender' => ['email' => 'votre_email@exemple.com'],
    'to' => [['email' => 'destinataire@test.com']],
    'subject' => 'Test API Brevo',
    'htmlContent' => '<h1>Test de l\'API Brevo</h1><p>Ceci est un test.</p>',
]);

try {
    $apiInstance->sendTransacEmail($sendSmtpEmail);
    echo "Email envoyé avec succès.";
} catch (Exception $e) {
    echo 'Erreur lors de l\'envoi de l\'email : ', $e->getMessage(), PHP_EOL;
}
?>
