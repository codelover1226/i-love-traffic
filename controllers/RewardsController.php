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
class RewardsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new RewardsModel();
    }
    public function addReward()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["admin_csrf_token"]) && isset($_POST["reward_description"]) && isset($_POST["reward_amount"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["username"]) || empty($_POST["admin_csrf_token"]) || empty($_POST["reward_description"]) || empty($_POST["reward_amount"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($adminController->getAdminCSRFToken() != $_POST["admin_csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["reward_amount"]) || $_POST["reward_amount"] < 0) {
                return ["success" => false, "message" => "Invalid reward amount."];
            }
            if (255 < strlen($_POST["reward_description"])) {
                return ["success" => false, "message" => "Description is too long. You can use max 255 characters."];
            }
            $memberController = new MembersController();
            $userInfo = $memberController->userInfoByUsername($_POST["username"]);
            if (empty($userInfo)) {
                return ["success" => false, "message" => "Couldn't find the user. Enter a valid username."];
            }
            $this->model->addRewards(["username" => $_POST["username"], "reward_description" => $_POST["reward_description"], "reward_amount" => $_POST["reward_amount"], "reward_date" => time()]);
            $memberController->addBalance($_POST["username"], $_POST["reward_amount"]);
            $siteSettingsController = new SiteSettingsController();
            $siteSettings = $siteSettingsController->getSettings();
            $rewardMessage = "Dear " . $userInfo["first_name"] . " " . $userInfo["last_name"] . "<br>";
            $rewardMessage .= "We have added \$" . $_POST["reward_amount"] . " in your balance as reward.";
            $rewardMessage .= "<br><br>Keep it up the good work.";
            SingleEmailSystem::sendEmail($siteSettings["admin_email"], $siteSettings["site_title"], $userInfo["email"], $userInfo["first_name"] . " " . $userInfo["last_name"], $siteSettings["site_title"] . " :: New Reward Notification", $rewardMessage);
            return ["success" => true, "message" => "Reward has been added."];
        }
    }
    public function totalAffiliateRewards($username)
    {
        return $this->model->totalAffiliateRewards($username);
    }
    public function affiliateRewardsList($username)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalAffiliateRewards($username);
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
        return $this->model->affilaiteRewardsList($username, 30, $offset);
    }
    public function affiliateRewardsPagination($username)
    {
        return $this->pagination(30, $this->totalAffiliateRewards($username), "rewards.php");
    }
}

?>