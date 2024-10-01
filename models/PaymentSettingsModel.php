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
class PaymentSettingsModel extends Model
{
    private $table = "ntk_payment_settings";
    public function getPaymentSettings($payment_method)
    {
        return $this->getSingle($this->table, "payment_method", $payment_method);
    }
    public function updatePaymentSettings($data, $payment_method)
    {
        return $this->updateData($this->table, "payment_method", $payment_method, $data);
    }
    public function allActivePaymentGateway()
    {
        return $this->getAll($this->table, 1000, 0, "ASC", "status", 1);
    }
}

?>