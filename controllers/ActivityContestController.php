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
class ActivityContestController extends Controller
{
    public function activityContestLeaderboard()
    {
        $contestController = new ContestSettingsController();
        $emailClicksController = new EmailClicksController();
        $contestInfo = $contestController->getContestSettings("Activity Contest");
        $leaderBoard = $emailClicksController->activityContestLeaderboard($contestInfo["start_date"], $contestInfo["end_date"]);
        return ["contest_info" => $contestInfo, "leaderboard" => $leaderBoard];
    }
}

?>