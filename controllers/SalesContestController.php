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
class SalesContestController extends Controller
{
    public function salesContestLeaderboard()
    {
        $contestController = new ContestSettingsController();
        $ordersController = new OrdersController();
        $contestInfo = $contestController->getContestSettings("Sales Contest");
        $leaderBoard = $ordersController->salesContestLeaderboard($contestInfo["start_date"], $contestInfo["end_date"]);
        return ["contest_info" => $contestInfo, "leaderboard" => $leaderBoard];
    }
}

?>