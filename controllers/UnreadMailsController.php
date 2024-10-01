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
class UnreadMailsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new UnreadMailsModel();
    }
    public function unreadMailsList($username)
    {
        return $this->model->unreadMailsList($username);
    }
    public function totalUnreadMails($username)
    {
        return $this->model->totalUnreadMails($username);
    }
    public function unreadMailsPagination($username)
    {
        $total = $this->totalUnreadMails($username);
        $limit = 30;
        $this->pagination($limit, $total, "unread-mails.php");
    }
}

?>