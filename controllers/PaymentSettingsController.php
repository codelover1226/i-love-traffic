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
class PaymentSettingsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new PaymentSettingsModel();
    }
    public function getSettings($payment_method)
    {
        return $this->model->getPaymentSettings($payment_method);
    }
    public function updateStripe()
    {
        if (isset($_POST["public_key"]) && isset($_POST["private_key"]) && isset($_POST["status"]) && isset($_POST["ipn_api_key"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["public_key"]) || empty($_POST["private_key"]) || empty($_POST["status"]) || empty($_POST["ipn_api_key"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (500 < strlen($_POST["public_key"])) {
                return ["success" => false, "message" => "Invalid Stripe API public key."];
            }
            if (500 < strlen($_POST["private_key"])) {
                return ["success" => false, "message" => "Invalid Stripe API private key."];
            }
            if (!is_numeric($_POST["status"]) || $_POST["status"] < 1 || 2 < $_POST["status"]) {
                return ["success" => false, "message" => "Invalid payment status."];
            }
            $this->model->updatePaymentSettings(["public_key" => $_POST["public_key"], "private_key" => $_POST["private_key"], "status" => $_POST["status"], "ipn_api_key" => $_POST["ipn_api_key"]], "Stripe");
            return ["success" => true, "message" => "Payment settings has been updated."];
        }
    }
    public function updateMollie()
    {
        if (isset($_POST["ipn_api_key"]) && isset($_POST["status"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["ipn_api_key"]) || empty($_POST["status"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (100 < strlen($_POST["ipn_api_key"])) {
                return ["success" => false, "message" => "Invalid Mollie API key."];
            }
            if (!is_numeric($_POST["status"]) || $_POST["status"] < 1 || 2 < $_POST["status"]) {
                return ["success" => false, "message" => "Invalid payment status."];
            }
            $this->model->updatePaymentSettings(["ipn_api_key" => $_POST["ipn_api_key"], "status" => $_POST["status"]], "Mollie");
            return ["success" => true, "message" => "Payment settings has been updated."];
        }
    }
    public function updateCoinbase()
    {
        if (isset($_POST["ipn_api_key"]) && isset($_POST["status"]) && isset($_POST["private_key"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["ipn_api_key"]) || empty($_POST["status"]) || empty($_POST["private_key"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (100 < strlen($_POST["ipn_api_key"])) {
                return ["success" => false, "message" => "Invalid API key."];
            }
            if (100 < strlen($_POST["private_key"])) {
                return ["success" => false, "message" => "Invalid Webhook key."];
            }
            if (!is_numeric($_POST["status"]) || $_POST["status"] < 1 || 2 < $_POST["status"]) {
                return ["success" => false, "message" => "Invalid payment status."];
            }
            $this->model->updatePaymentSettings(["ipn_api_key" => $_POST["ipn_api_key"], "status" => $_POST["status"], "private_key" => $_POST["private_key"]], "Coinbase");
            return ["success" => true, "message" => "Payment settings has been updated."];
        }
    }
    public function updateBinance()
    {
        if (isset($_POST["status"]) && isset($_POST["public_key"]) && isset($_POST["ipn_api_key"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["status"]) || empty($_POST["public_key"]) || empty($_POST["ipn_api_key"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (200 < strlen($_POST["public_key"])) {
                return ["success" => false, "message" => "Invalid API Key."];
            }
            if (200 < strlen($_POST["ipn_api_key"])) {
                return ["success" => false, "message" => "Invalid Secret Key."];
            }
            if (!is_numeric($_POST["status"]) || $_POST["status"] < 1 || 2 < $_POST["status"]) {
                return ["success" => false, "message" => "Invalid payment status."];
            }
            $this->model->updatePaymentSettings(["status" => $_POST["status"], "public_key" => $_POST["public_key"], "ipn_api_key" => $_POST["ipn_api_key"]], "Binance");
            return ["success" => true, "message" => "Payment settings has been updated."];
        }
    }
    public function updatePayPal()
    {
        if (isset($_POST["status"]) && isset($_POST["public_key"]) && isset($_POST["private_key"]) && isset($_POST["ipn_api_key"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["status"]) || empty($_POST["public_key"]) || empty($_POST["private_key"]) || empty($_POST["ipn_api_key"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (200 < strlen($_POST["public_key"])) {
                return ["success" => false, "message" => "Invalid Client ID."];
            }
            if (200 < strlen($_POST["private_key"])) {
                return ["success" => false, "message" => "Invalid Secret key."];
            }
            if (200 < strlen($_POST["ipn_api_key"])) {
                return ["success" => false, "message" => "Invalid Webhook secret key."];
            }
            if (!is_numeric($_POST["status"]) || $_POST["status"] < 1 || 2 < $_POST["status"]) {
                return ["success" => false, "message" => "Invalid payment status."];
            }
            $this->model->updatePaymentSettings(["status" => $_POST["status"], "private_key" => $_POST["private_key"], "public_key" => $_POST["public_key"], "ipn_api_key" => $_POST["ipn_api_key"]], "PayPal");
            return ["success" => true, "message" => "Payment settings has been updated."];
        }
    }
    public function allActivePaymentGateway()
    {
        return $this->model->allActivePaymentGateway();
    }
}

?>