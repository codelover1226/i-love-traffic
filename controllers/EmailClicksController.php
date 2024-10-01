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
class EmailClicksController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new EmailClicksModel();
    }
    public function emailClicksList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalEmailClicks();
            $total_offset = ceil($total / 30);
            if ($_GET["page"] - 1 < 0) {
                $offset = 0;
            } else {
                if ($total_offset < $_GET["page"] - 1) {
                    $offset = 0;
                } else {
                    $offset = ($_GET["page"] - 1) * 30;
                }
            }
        }
        return $this->model->emailClickList(30, $offset);
    }
    public function emailClicksPagination()
    {
        return $this->pagination(30, $this->totalEmailClicks(), "email-clicks.php");
    }
    public function totalEmailClicks()
    {
        return $this->model->totalEmailClicks();
    }
    public function activityContestLeaderboard($startDate, $endDate)
    {
        return $this->model->activityContestLeaderboard($startDate, $endDate);
    }
    public function topClickersThisMonth()
    {
        return $this->model->topClickersThisMonth();
    }
    public function checkUserEmailClick($username, $emailId)
    {
        return $this->model->checkUserEmailClick($username, $emailId);
    }
    public function addNewEmailClick($data)
    {
        return $this->model->addNewClick($data);
    }
    public function deleteOldHistory()
    {
        return $this->model->deleteOldHistory();
    }
    public function totalClicksToday($username)
    {
        return $this->model->totalClicksToday($username);
    }
}

?>