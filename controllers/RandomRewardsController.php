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
class RandomRewardsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new RandomRewardsModel();
    }
    public function getSettings()
    {
        return $this->model->getSettings();
    }
    public function updateSettings()
    {
        if (isset($_POST["credits_rewards"]) && isset($_POST["banner_credits_rewards"]) && isset($_POST["text_ad_rewards"]) && isset($_POST["money_rewards"]) && isset($_POST["clicks_required_for_money"]) && isset($_POST["clicks_required_for_rewards"]) && isset($_POST["admin_csrf_token"])) {
            if (empty($_POST["credits_rewards"]) || empty($_POST["banner_credits_rewards"]) || empty($_POST["text_ad_rewards"]) || empty($_POST["money_rewards"]) || empty($_POST["clicks_required_for_money"]) || empty($_POST["clicks_required_for_rewards"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if (!is_numeric($_POST["credits_rewards"]) || $_POST["credits_rewards"] < 0) {
                return ["success" => false, "message" => "Invalid value for reward email credits."];
            }
            if (!is_numeric($_POST["banner_credits_rewards"]) || $_POST["banner_credits_rewards"] < 0) {
                return ["success" => false, "message" => "Invalid value for reward banner ad credits."];
            }
            if (!is_numeric($_POST["text_ad_rewards"]) || $_POST["text_ad_rewards"] < 0) {
                return ["success" => false, "message" => "Invalid value for reward text ad credits."];
            }
            if (!is_numeric($_POST["money_rewards"]) || $_POST["money_rewards"] < 0) {
                return ["success" => false, "message" => "Invalid value for reward money."];
            }
            if (!is_numeric($_POST["clicks_required_for_money"]) || $_POST["clicks_required_for_money"] < 0) {
                return ["success" => false, "message" => "Invalid value for click required for money."];
            }
            if (!is_numeric($_POST["clicks_required_for_rewards"]) || $_POST["clicks_required_for_rewards"] < 0) {
                return ["success" => false, "message" => "Invalid value for click required for rewards."];
            }
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_POST["admin_csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            $this->model->updateSettings(["credits_rewards" => $_POST["credits_rewards"], "banner_credits_rewards" => $_POST["banner_credits_rewards"], "text_ad_rewards" => $_POST["text_ad_rewards"], "money_rewards" => $_POST["money_rewards"], "clicks_required_for_money" => $_POST["clicks_required_for_money"], "clicks_required_for_rewards" => $_POST["clicks_required_for_rewards"]]);
            return ["success" => true, "message" => "Random reward system has been updated."];
        }
    }
    public function checkTodayClickRewards($username, $total_clicks_today)
    {
        return $this->model->checkClickRewards($username, $total_clicks_today);
    }
    public function giveRandomRewards($username, $total_clicks_today)
    {
        $settingsData = $this->getSettings();
        $modulous = $total_clicks_today % $settingsData["clicks_required_for_rewards"];
        if ($settingsData["clicks_required_for_rewards"] <= $total_clicks_today && $modulous == 0 && !$this->checkTodayClickRewards($username, $total_clicks_today)) {
            if ($settingsData["clicks_required_for_money"] <= $total_clicks_today) {
                $randomRewardType = ["Email Credits", "Banner Ad Credits", "Text Ad Credits", "Money"];
                $randomRewards = mt_rand(0, 3);
                $membersController = new MembersController();
                if ($randomRewardType[$randomRewards] == "Email Credits") {
                    $membersController->addEmailCredits($username, $settingsData["credits_rewards"]);
                    $this->model->addRewardHistory(["username" => $username, "rewards" => $settingsData["credits_rewards"] . " email credits.", "reward_timestamp" => time(), "total_clicks" => $total_clicks_today]);
                    return "You got " . $settingsData["credits_rewards"] . " email credits as reward for reading emails.";
                }
                if ($randomRewardType[$randomRewards] == "Banner Ad Credits") {
                    $membersController->increaseMemberBannerAdCredits($username, $settingsData["banner_credits_rewards"]);
                    $this->model->addRewardHistory(["username" => $username, "rewards" => $settingsData["banner_credits_rewards"] . " banner ad credits.", "reward_timestamp" => time(), "total_clicks" => $total_clicks_today]);
                    return "You got " . $settingsData["banner_credits_rewards"] . " banner ad credits as reward for reading emails.";
                }
                if ($randomRewardType[$randomRewards] == "Text Ad Credits") {
                    $membersController->increaseMemberTextAdCredits($username, $settingsData["text_ad_rewards"]);
                    $this->model->addRewardHistory(["username" => $username, "rewards" => $settingsData["text_ad_rewards"] . " text ad credits.", "reward_timestamp" => time(), "total_clicks" => $total_clicks_today]);
                    return "You got " . $settingsData["text_ad_rewards"] . " text ad credits as reward for reading emails.";
                }
                if ($randomRewardType[$randomRewards] == "Money") {
                    $membersController->addBalance($username, $settingsData["money_rewards"]);
                    $this->model->addRewardHistory(["username" => $username, "rewards" => "\$" . $settingsData["money_rewards"] . " USD.", "reward_timestamp" => time(), "total_clicks" => $total_clicks_today]);
                    return "You got \$" . $settingsData["money_rewards"] . " as reward for reading emails.";
                }
            } else {
                $randomRewardType = ["Email Credits", "Banner Ad Credits", "Text Ad Credits"];
                $randomRewards = mt_rand(0, 2);
                $membersController = new MembersController();
                if ($randomRewardType[$randomRewards] == "Email Credits") {
                    $membersController->addEmailCredits($username, $settingsData["credits_rewards"]);
                    $this->model->addRewardHistory(["username" => $username, "rewards" => $settingsData["credits_rewards"] . " email credits.", "reward_timestamp" => time(), "total_clicks" => $total_clicks_today]);
                    return "You got " . $settingsData["credits_rewards"] . " email credits as reward for reading emails.";
                }
                if ($randomRewardType[$randomRewards] == "Banner Ad Credits") {
                    $membersController->increaseMemberBannerAdCredits($username, $settingsData["banner_credits_rewards"]);
                    $this->model->addRewardHistory(["username" => $username, "rewards" => $settingsData["banner_credits_rewards"] . " banner ad credits.", "reward_timestamp" => time(), "total_clicks" => $total_clicks_today]);
                    return "You got " . $settingsData["banner_credits_rewards"] . " banner ad credits as reward for reading emails.";
                }
                if ($randomRewardType[$randomRewards] == "Text Ad Credits") {
                    $membersController->increaseMemberTextAdCredits($username, $settingsData["text_ad_rewards"]);
                    $this->model->addRewardHistory(["username" => $username, "rewards" => $settingsData["text_ad_rewards"] . " text ad credits.", "reward_timestamp" => time(), "total_clicks" => $total_clicks_today]);
                    return "You got " . $settingsData["text_ad_rewards"] . " text ad credits as reward for reading emails.";
                }
            }
        }
    }
}

?>