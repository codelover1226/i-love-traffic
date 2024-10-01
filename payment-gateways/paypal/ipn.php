<?php
require_once "../../load_classes.php";
$payload = file_get_contents("php://input");
$payloadData = json_decode($payload, true);
$transactionID = $payloadData["resource"]["id"];
$paymentStatus = $payloadData["resource"]["status"];
$headers = getallheaders();
$paymentSettingsController = new PaymentSettingsController();
$paymentSettingsData = $paymentSettingsController->getSettings("PayPal");
$clientId = $paymentSettingsData["public_key"];
$clientSecret = $paymentSettingsData["private_key"];
$apiEndpoint = "https://api.paypal.com";
$tokenEndpoint = $apiEndpoint . "/v1/oauth2/token";
$webhookVerifyEndpoint = $apiEndpoint . "/v1/notifications/verify-webhook-signature";
$ch = curl_init($tokenEndpoint);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json", "Accept-Language: en_US"]);
curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$result = json_decode($response, true);
$accessToken = $result["access_token"];
$signaturePayload = json_encode(["auth_algo" => $headers["Paypal-Auth-Algo"], "cert_url" => $headers["Paypal-Cert-Url"], "transmission_id" => $headers["Paypal-Transmission-Id"], "transmission_sig" => $headers["Paypal-Transmission-Sig"], "transmission_time" => $headers["Paypal-Transmission-Time"], "webhook_id" => $paymentSettingsData["ipn_api_key"], "webhook_event" => json_decode($payload, true)]);
$ch = curl_init($webhookVerifyEndpoint);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $signaturePayload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Authorization: Bearer " . $accessToken]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$decodedWebhookVerifyResponse = json_decode($response, true);
if ($httpCode === 200 && $decodedWebhookVerifyResponse["verification_status"] === "SUCCESS") {
    if ($paymentStatus == "APPROVED") {
        $amount = $payloadData["resource"]["purchase_units"]["amount"]["value"];
        $captureEndpoint = "https://api.paypal.com/v2/checkout/orders/" . $transactionID . "/capture";
        $ch = curl_init($tokenEndpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json", "Accept-Language: en_US"]);
        curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $result = json_decode($response, true);
        $accessToken = $result["access_token"];
        $ch = curl_init($captureEndpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Authorization: Bearer " . $accessToken]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $decodedResponse = json_decode($response, true);
    } else {
        if ($paymentStatus == "COMPLETED") {
            $tmpTransactionsController = new TmpTransactionsController();
            $orderId = $payloadData["resource"]["supplementary_data"]["related_ids"]["order_id"];
            $paymentDetails = $tmpTransactionsController->getInfo($orderId, "PayPal");
            $ipnController = new PaymentIPNController();
            $amount = $payloadData["resource"]["amount"]["value"];
            if ($paymentDetails["amount"] <= $amount) {
                $ipnController->processOrder($paymentDetails["username"], $paymentDetails["product_type"], $paymentDetails["product"], $amount, "PayPal", $transactionID, $paymentDetails["website_link"]);
                $tmpTransactionsController->delete($orderId, "PayPal");
            }
        }
    }
}

?>