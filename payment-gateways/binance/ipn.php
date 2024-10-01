<?php
ini_set("log_errors", 0);
require_once "../../load_classes.php";
$webhookResponseJson = file_get_contents("php://input");
if (!empty($webhookResponseJson)) {
    $webhookResObj = json_decode($webhookResponseJson);
    if ($webhookResObj->bizType == "PAY" && $webhookResObj->bizStatus == "PAY_SUCCESS") {
        $tmpTransactionsController = new TmpTransactionsController();
        $paymentDetails = $tmpTransactionsController->getInfo($webhookResObj->bizId, "Binance");
        $webhookDataObj = $webhookResObj->data;
        $webhookDataArr = json_decode($webhookDataObj, true);
        if (isset($webhookDataArr["transactionId"]) && isset($webhookDataArr["paymentInfo"])) {
            $ipnController = new PaymentIPNController();
            if ($paymentDetails["amount"] <= $webhookDataArr["paymentInfo"]["paymentInstructions"][0]["amount"]) {
                $ipnController->processOrder($paymentDetails["username"], $paymentDetails["product_type"], $paymentDetails["product"], $paymentDetails["amount"], "Binance", $webhookDataArr["transactionId"], $paymentDetails["website_link"]);
                $tmpTransactionsController->delete($webhookResObj->bizId, "Binance");
            }
        }
    }
}

?>