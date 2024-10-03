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
class ReceivedMailsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new ReceivedMailsModel();
    }
    public function receivedMailsList($username)
    {
        return $this->model->receivedMailsList($username);
    }
    public function totalReceivedMails($username)
    {
        return $this->model->totalReceivedMails($username);
    }
    public function receivedMailsPagination($username)
    {
        $total = $this->totalReceivedMails($username);
        $limit = 30;
        $this->pagination($limit, $total, "received-mails.php");
    }
}

?>