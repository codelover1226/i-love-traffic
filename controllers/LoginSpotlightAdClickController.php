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
class LoginSpotlightAdClickController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new LoginSpotlightAdClickModel();
    }
    public function getTodayAdCount($username, $id)
    {
        return $this->model->getTodayClickHistoryCount($username, $id);
    }
    public function addClickHistory($data)
    {
        return $this->model->addClickHistory($data);
    }
}

?>