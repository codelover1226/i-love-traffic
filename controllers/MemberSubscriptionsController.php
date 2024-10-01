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
class MemberSubscriptionsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new MemberSubscriptionsModel();
    }
    public function addSubscription($username, $subscriptionID, $paymentGateway, $productID)
    {
        $this->model->addNewSubscription($username, $subscriptionID, $paymentGateway, $productID);
    }
    public function getSubscriptionDetails($subscriptionID)
    {
        return $this->model->getSubscriptionDetails($subscriptionID);
    }
    public function updateSubscriptionDetails($id, $data)
    {
    }
}

?>