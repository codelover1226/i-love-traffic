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
class MembersTokenController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new MembersTokenModel();
    }
    public function addToken($data)
    {
        return $this->model->addToken($data);
    }
    public function deleteUserAllToken($username)
    {
        return $this->model->deleteAllUserToken($username);
    }
    public function tokenDetails($token)
    {
        return $this->model->tokenDetails($token);
    }
}

?>