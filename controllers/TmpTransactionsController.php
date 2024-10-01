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
class TmpTransactionsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new TmpTransactionsModel();
    }
    public function add($data)
    {
        return $this->model->add($data);
    }
    public function getInfo($trx_id, $payment_method)
    {
        return $this->model->getInfo($trx_id, $payment_method);
    }
    public function delete($trx_id, $payment_method)
    {
        return $this->model->delete($trx_id, $payment_method);
    }
    public function deleteOldHistory()
    {
        return $this->model->deleteOldHistory();
    }
}

?>