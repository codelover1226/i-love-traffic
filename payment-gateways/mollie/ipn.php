<?php
require_once "vendor/autoload.php";
require_once "../../load_classes.php";
try {
    if (isset($_POST["id"])) {
        $paymentSettingsController = new PaymentSettingsController();
        $mollieData = $paymentSettingsController->getSettings("Mollie");
        $mollie = new Mollie\Api\MollieApiClient();
        $mollie->setApiKey($mollieData["ipn_api_key"]);
        $payment = $mollie->payments->get($_POST["id"]);
        if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
            $tmpTransactionsController = new TmpTransactionsController();
            $paymentDetails = $tmpTransactionsController->getInfo($_POST["id"], "Mollie");
            $ipnController = new PaymentIPNController();
            $ipnController->processOrder($paymentDetails["username"], $paymentDetails["product_type"], $paymentDetails["product"], $paymentDetails["amount"], "Mollie", $orderId, $paymentDetails["website_link"]);
            $tmpTransactionsController->delete($_POST["id"], "Mollie");
        }
    }
} catch (Mollie\Api\Exceptions\ApiException $e) {
}

?>