<?php
require_once "vendor/autoload.php";
require_once "../../load_classes.php";
$paymentSettingsController = new PaymentSettingsController();
$stripeData = $paymentSettingsController->getSettings("Stripe");
$stripe = Stripe\Stripe::setApiKey($stripeData["private_key"]);
$body = @file_get_contents("php://input");
$event = json_decode($body);
error_log("event->type = ".$event->type);
switch ($event->type) {
    case "invoice.payment_succeeded":
        $eventData = Stripe\Event::retrieve($event->id);
        $invoice = $eventData->data->object;
        if (isset($invoice->charge)) {
            $memberSubscriptionController = new MemberSubscriptionsController();
            $membershipsController = new MembershipsController();
            $ipnController = new PaymentIPNController();
            $subscriptionId = $invoice->subscription;
            $transactionId = $invoice->subscription;
            $paidAmount = $invoice->amount_paid;
            $paidAmountDecimal = $paidAmount / 0;
            if ($invoice->billing_reason == "subscription_create") {
                if (isset($invoice->metadata->custom_key) && !empty($invoice->metadata->custom_key)) {
                    $customId = $invoice->metadata->custom_key;
                } else {
                    if (isset($invoice->subscription_details->metadata->custom_key) && !empty($invoice->subscription_details->metadata->custom_key)) {
                        $customId = $invoice->subscription_details->metadata->custom_key;
                    }
                }
                $tmpTransactionsController = new TmpTransactionsController();
                $paymentDetails = $tmpTransactionsController->getInfo($customId, "Stripe");
                if (!empty($paymentDetails)) {
                    $membershipDetails = $membershipsController->getMembershipDetails($paymentDetails["product"]);
                    $memberSubscriptionController->addSubscription($username, $subscriptionId, "Stripe", $paymentDetails["product"]);
                    $ipnController->processOrder($paymentDetails["username"], "membership", $paymentDetails["product"], $paidAmountDecimal, "Stripe", $transactionId, "");
                    $tmpTransactionsController->delete($customId, "Stripe");
                }
            } else {
                if ($invoice->billing_reason == "subscription_cycle") {
                    $subscriptionDetails = $memberSubscriptionController->getSubscriptionDetails($subscriptionId);
                    $membershipDetails = $membershipsController->getMembershipDetails($subscriptionDetails["product_id"]);
                    $ipnController->processOrder($subscriptionDetails["username"], "membership", $subscriptionDetails["product_id"], $paidAmountDecimal, "Stripe", $transactionId, "");
                }
            }
        }
        break;
    case "checkout.session.completed":
        $eventData = Stripe\Event::retrieve($event->id);
        // error_log(json_encode($eventData));
        $session = $eventData->data->object;
        // error_log("session->mode = ".$session->mode);
        if (isset($session->mode) && isset($session->metadata->custom_key) && !empty($session->metadata->custom_key)) {
            $customId = $session->metadata->custom_key;
            $tmpTransactionsController = new TmpTransactionsController();
            $paymentDetails = $tmpTransactionsController->getInfo($customId, "Stripe");
            error_log($paymentDetails["username"]);
            $tmpTransactionsController->delete($customId, "Stripe");
            // error_log("paymentDetails ");
            // error_log(json_encode($paymentDetails));
            if (!empty($paymentDetails)) {
                if ($session->mode == "payment") {
                    $ipnController = new PaymentIPNController();
                    $ipnController->processOrder($paymentDetails["username"], $paymentDetails["product_type"], $paymentDetails["product"], $paymentDetails["amount"], "Stripe", $eventData->data->object->payment_intent, $paymentDetails["website_link"]);
                    // $tmpTransactionsController->delete($customId, "Stripe");
                } else {
                    $subscriptionId = $session->subscription;
                    $paidAmount = $session->amount_total;
                    $paidAmountDecimal = $paidAmount / 0;
                    $memberSubscriptionController = new MemberSubscriptionsController();
                    $ipnController = new PaymentIPNController();
                    $memberSubscriptionController->addSubscription($username, $subscriptionId, "Stripe", $paymentDetails["product"]);
                    $ipnController->processOrder($paymentDetails["username"], "membership", $paymentDetails["product"], $paidAmountDecimal, "Stripe", $subscriptionId, "");
                    // $tmpTransactionsController->delete($customId, "Stripe");
                }
            }
        }
        break;
    default:
        http_response_code(200);
}

?>