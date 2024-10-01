<?php
$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    header("Location: / ");
    exit;
}
if (file_exists("load_classes.php")) {
    require_once "load_classes.php";
} else {
    if (file_exists("../load_classes.php")) {
        require_once "../load_classes.php";
    }
}
class MemberSubscriptionsModel extends Model
{
    private $table = "ntk_membership_subscriptions";
    public function addNewSubscription($username, $subscriptionID, $paymentGateway, $productID)
    {
        $this->insertData($this->table, ["username" => $username, "subscription_id" => $subscriptionID, "payment_gateway" => $paymentGateway, "product_type" => "membership", "product_id" => (string) $productID]);
    }
    public function getSubscriptionDetails($subscriptionID)
    {
        return $this->getSingle($this->table, "subscription_id", $subscriptionID);
    }
    public function updateSubscription($id, $data)
    {
        $this->updateData($this->table, "id", $id, $data);
    }
}

?>