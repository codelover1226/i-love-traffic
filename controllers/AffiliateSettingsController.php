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
class AffiliateSettingsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new AffiliateSettingsModel();
    }
    public function getSettings()
    {
        return $this->model->getSettings();
    }
    public function updateSettings()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["minimum_withdraw"]) && isset($_POST["paypal"]) && isset($_POST["btc_coinbase"]) && isset($_POST["skrill"]) && isset($_POST["transfer_wise"]) && isset($_POST["perfect_money"]) && isset($_POST["eth_wallet"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["minimum_withdraw"]) || empty($_POST["paypal"]) || empty($_POST["btc_coinbase"]) || empty($_POST["skrill"]) || empty($_POST["transfer_wise"]) || empty($_POST["perfect_money"]) || empty($_POST["eth_wallet"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($adminController->getAdminCSRFToken() != $_POST["admin_csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["minimum_withdraw"]) || $_POST["minimum_withdraw"] < 0) {
                return ["success" => false, "message" => "Invalid minimum withdraw limit."];
            }
            if (!is_numeric($_POST["paypal"]) || !is_numeric($_POST["btc_coinbase"]) || !is_numeric($_POST["skrill"]) || !is_numeric($_POST["transfer_wise"]) || !is_numeric($_POST["perfect_money"]) || !is_numeric($_POST["eth_wallet"])) {
                return ["success" => false, "message" => "Invalid payment status."];
            }
            if ($_POST["paypal"] < 1 && 2 < $_POST["paypal"]) {
                return ["success" => false, "message" => "Invalid payment status."];
            }
            if ($_POST["btc_coinbase"] < 1 && 2 < $_POST["btc_coinbase"]) {
                return ["success" => false, "message" => "Invalid payment status."];
            }
            if ($_POST["skrill"] < 1 && 2 < $_POST["skrill"]) {
                return ["success" => false, "message" => "Invalid payment status."];
            }
            if ($_POST["transfer_wise"] < 1 && 2 < $_POST["transfer_wise"]) {
                return ["success" => false, "message" => "Invalid payment status."];
            }
            if ($_POST["perfect_money"] < 1 && 2 < $_POST["perfect_money"]) {
                return ["success" => false, "message" => "Invalid payment status."];
            }
            if ($_POST["eth_wallet"] < 1 && 2 < $_POST["eth_wallet"]) {
                return ["success" => false, "message" => "Invalid payment status."];
            }
            $this->model->updateSettings(["minimum_withdraw" => $_POST["minimum_withdraw"], "paypal" => $_POST["paypal"], "btc_coinbase" => $_POST["btc_coinbase"], "skrill" => $_POST["skrill"], "transfer_wise" => $_POST["transfer_wise"], "perfect_money" => $_POST["perfect_money"], "eth_wallet" => $_POST["eth_wallet"]]);
            return ["success" => true, "message" => "Affiliate settings has been updated."];
        }
    }
}

?>