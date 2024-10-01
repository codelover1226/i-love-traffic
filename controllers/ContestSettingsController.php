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
class ContestSettingsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new ContestSettingsModel();
    }
    public function getContestSettings($contest)
    {
        return $this->model->getSettings($contest);
    }
    public function updateContestSettings($contest)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $adminController = new AdminController();
            if (isset($_POST["status"]) && isset($_POST["start_date"]) && isset($_POST["end_date"]) && isset($_POST["first_prize_credits"]) && isset($_POST["first_prize_banner_credits"]) && isset($_POST["first_prize_text_credits"]) && isset($_POST["first_prize_money"]) && isset($_POST["second_prize_credits"]) && isset($_POST["second_prize_banner_credits"]) && isset($_POST["second_prize_text_credits"]) && isset($_POST["second_prize_money"]) && isset($_POST["third_prize_credits"]) && isset($_POST["third_prize_banner_credits"]) && isset($_POST["third_prize_text_credits"]) && isset($_POST["third_prize_money"]) && isset($_POST["admin_csrf_token"])) {
                if ($this->arrayCheck($_POST)) {
                    return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
                }
                if (empty($_POST["status"]) || empty($_POST["start_date"]) || empty($_POST["end_date"]) || empty($_POST["first_prize_credits"]) || empty($_POST["first_prize_banner_credits"]) || empty($_POST["first_prize_text_credits"]) || empty($_POST["first_prize_money"]) || empty($_POST["second_prize_credits"]) || empty($_POST["second_prize_banner_credits"]) || empty($_POST["second_prize_text_credits"]) || empty($_POST["second_prize_money"]) || empty($_POST["third_prize_credits"]) || empty($_POST["third_prize_banner_credits"]) || empty($_POST["third_prize_text_credits"]) || empty($_POST["third_prize_money"]) || empty($_POST["admin_csrf_token"])) {
                    return ["success" => false, "message" => "All fields are required."];
                }
                if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                    return ["success" => false, "message" => "Invalid request."];
                }
                if ($_POST["status"] < 1 || 2 < $_POST["status"]) {
                    return ["success" => false, "message" => "Invalid status."];
                }
                if (!is_numeric($_POST["first_prize_credits"]) || $_POST["first_prize_credits"] < 0) {
                    return ["success" => false, "message" => "Invalid first prize credits."];
                }
                if (!is_numeric($_POST["first_prize_banner_credits"]) || $_POST["first_prize_banner_credits"] < 0) {
                    return ["success" => false, "message" => "Invalid first prize banner credits."];
                }
                if (!is_numeric($_POST["first_prize_text_credits"]) || $_POST["first_prize_text_credits"] < 0) {
                    return ["success" => false, "message" => "Invalid first prize text ad credits."];
                }
                if (!is_numeric($_POST["first_prize_money"]) || $_POST["first_prize_money"] < 0) {
                    return ["success" => false, "message" => "Invalid first prize money amount."];
                }
                if (!is_numeric($_POST["second_prize_credits"]) || $_POST["second_prize_credits"] < 0) {
                    return ["success" => false, "message" => "Invalid second prize credits."];
                }
                if (!is_numeric($_POST["second_prize_banner_credits"]) || $_POST["second_prize_banner_credits"] < 0) {
                    return ["success" => false, "message" => "Invalid second prize banner credits."];
                }
                if (!is_numeric($_POST["second_prize_text_credits"]) || $_POST["second_prize_text_credits"] < 0) {
                    return ["success" => false, "message" => "Invalid second prize text ad credits."];
                }
                if (!is_numeric($_POST["second_prize_money"]) || $_POST["second_prize_money"] < 0) {
                    return ["success" => false, "message" => "Invalid second prize money amount."];
                }
                if (!is_numeric($_POST["third_prize_credits"]) || $_POST["third_prize_credits"] < 0) {
                    return ["success" => false, "message" => "Invalid third prize credits."];
                }
                if (!is_numeric($_POST["third_prize_banner_credits"]) || $_POST["third_prize_banner_credits"] < 0) {
                    return ["success" => false, "message" => "Invalid third prize banner credits."];
                }
                if (!is_numeric($_POST["third_prize_text_credits"]) || $_POST["third_prize_text_credits"] < 0) {
                    return ["success" => false, "message" => "Invalid third prize text ad credits."];
                }
                if (!is_numeric($_POST["third_prize_money"]) || $_POST["third_prize_money"] < 0) {
                    return ["success" => false, "message" => "Invalid third prize money amount."];
                }
                $start_date = explode("-", $_POST["start_date"]);
                $end_date = explode("-", $_POST["end_date"]);
                if (!checkdate($start_date[1], $start_date[2], $start_date[0])) {
                    return ["success" => false, "message" => "Invalid start date."];
                }
                if (!checkdate($end_date[1], $end_date[2], $end_date[0])) {
                    return ["success" => false, "message" => "Invalid end date."];
                }
                $this->model->updateContestSettings(["status" => $_POST["status"], "start_date" => $_POST["start_date"], "end_date" => $_POST["end_date"], "first_prize_credits" => $_POST["first_prize_credits"], "first_prize_banner_credits" => $_POST["first_prize_banner_credits"], "first_prize_text_credits" => $_POST["first_prize_text_credits"], "first_prize_money" => $_POST["first_prize_money"], "second_prize_credits" => $_POST["second_prize_credits"], "second_prize_banner_credits" => $_POST["second_prize_banner_credits"], "second_prize_text_credits" => $_POST["second_prize_text_credits"], "second_prize_money" => $_POST["second_prize_money"], "third_prize_credits" => $_POST["third_prize_credits"], "third_prize_banner_credits" => $_POST["third_prize_banner_credits"], "third_prize_text_credits" => $_POST["third_prize_text_credits"], "third_prize_money" => $_POST["third_prize_money"]], $contest);
                return ["success" => true, "message" => "Contest settings has been updated."];
            }
        }
    }
}

?>