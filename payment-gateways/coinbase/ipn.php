<?php
require_once "../../load_classes.php";
$requestBody = @file_get_contents("php://input");
if (!function_exists("getallheaders")) {
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == "HTTP_") {
                $headers[str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}
$requestHeaders = getallheaders();
if (!empty($requestBody) && !empty($requestHeaders) && isset($requestHeaders["X-Cc-Webhook-Signature"])) {
    $paymentSettingsController = new PaymentSettingsController();
    $coinbaseData = $paymentSettingsController->getSettings("Coinbase");
    try {
        $payload = trim($requestBody);
        $secretKey = $coinbaseData["private_key"];
        $signature = hash_hmac("sha256", $payload, $secretKey);
        if (hash_equals($signature, $requestHeaders["X-Cc-Webhook-Signature"])) {
            $eventJson = json_decode($requestBody);
            if (isset($eventJson->event->data->code)) {
                $eventID = $eventJson->event->data->code;
                $tmpTrxController = new TmpTransactionsController();
                $paymentDetails = $tmpTrxController->getInfo($eventID, "Coinbase Commerce");
                if (isset($eventJson->event->type) && !empty($paymentDetails) && $eventJson->event->type == "charge:confirmed") {
                    $ipnController = new PaymentIPNController();
                    $ipnController->processOrder($paymentDetails["username"], $paymentDetails["product_type"], $paymentDetails["product"], $paymentDetails["amount"], "Coinbase Commerce", $eventJson->event->data->payments[0]->transaction_id, $paymentDetails["website_link"]);
                    $tmpTrxController->delete($eventID, "Coinbase Commerce");
                }
            }
        }
    } catch (Exception $e) {
    }
}

?>