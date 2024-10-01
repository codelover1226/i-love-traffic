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
class MembershipsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new MembershipsModel();
    }
    public function addMembership()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["membership_title"]) && isset($_POST["sales_commission"]) && isset($_POST["clicks_commission"]) && isset($_POST["timer_seconds"]) && isset($_POST["price"]) && isset($_POST["admin_csrf_token"]) && isset($_POST["subscription_type"]) && isset($_POST["email_sending_limit"]) && isset($_POST["bonus_email_credits"]) && isset($_POST["bonus_text_ad_credits"]) && isset($_POST["bonus_banner_credits"]) && isset($_POST["credits_per_click"]) && isset($_POST["max_recipient"]) && isset($_POST["hidden"]) && isset($_POST["chat_gpt_access"]) && isset($_POST["chat_gpt_prompt_limit"]) && isset($_POST["stripe_price_id"]) && isset($_POST["status"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["membership_title"]) || empty($_POST["timer_seconds"]) || empty($_POST["subscription_type"]) || empty($_POST["admin_csrf_token"]) || empty($_POST["email_sending_limit"]) || empty($_POST["credits_per_click"]) || empty($_POST["max_recipient"]) || empty($_POST["hidden"]) || empty($_POST["chat_gpt_access"]) || empty($_POST["status"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["timer_seconds"]) || $_POST["timer_seconds"] < 0) {
                return ["success" => false, "message" => "Invalid timer (seconds)."];
            }
            if (!is_numeric($_POST["price"]) || $_POST["price"] < 0) {
                return ["success" => false, "message" => "Invalid price."];
            }
            if (!is_numeric($_POST["max_recipient"]) || $_POST["max_recipient"] < 0) {
                return ["success" => false, "message" => "Invalid max recipient."];
            }
            if (!is_numeric($_POST["clicks_commission"]) || $_POST["clicks_commission"] < 0) {
                return ["success" => false, "message" => "Invalid click commission."];
            }
            if (!is_numeric($_POST["email_sending_limit"]) || $_POST["email_sending_limit"] < 0) {
                return ["success" => false, "message" => "Invalid daily email sending limit."];
            }
            if (!is_numeric($_POST["sales_commission"]) || $_POST["sales_commission"] < 0) {
                return ["success" => false, "message" => "Invalid sales commission."];
            }
            if (!is_numeric($_POST["bonus_email_credits"]) || $_POST["bonus_email_credits"] < 0) {
                return ["success" => false, "message" => "Invalid bonus email credits."];
            }
            if (!is_numeric($_POST["bonus_text_ad_credits"]) || $_POST["bonus_text_ad_credits"] < 0) {
                return ["success" => false, "message" => "Invalid bonus text ad credits."];
            }
            if (!is_numeric($_POST["bonus_banner_credits"]) || $_POST["bonus_banner_credits"] < 0) {
                return ["success" => false, "message" => "Invalid bonus banner ad credits."];
            }
            if (!is_numeric($_POST["credits_per_click"]) || $_POST["credits_per_click"] < 0) {
                return ["success" => false, "message" => "Invalid credits per click."];
            }
            if (!is_numeric($_POST["hidden"]) || $_POST["hidden"] < 0 || 2 < $_POST["hidden"]) {
                return ["success" => false, "message" => "Invalid hidden status."];
            }
            if ($_POST["status"] != 1 && $_POST["status"] != 2) {
                return ["success" => false, "message" => "Invalid value for status."];
            }
            if ($_POST["chat_gpt_access"] != 1 && $_POST["chat_gpt_access"] != 2) {
                return ["success" => false, "message" => "Invalid value for ChatGPT access."];
            }
            if ($_POST["subscription_type"] != 1 && $_POST["subscription_type"] != 2 && $_POST["subscription_type"] != 3 && $_POST["subscription_type"] != 4) {
                return ["success" => false, "message" => "Invalid subscription type."];
            }
            if (255 < strlen($_POST["membership_title"])) {
                return ["success" => false, "message" => "Membership titile is too long."];
            }
            if ($_POST["subscription_type"] == 1 && 0 < $_POST["price"]) {
                return ["success" => false, "message" => "You can't add price for free membership."];
            }
            if ($_POST["subscription_type"] != 1 && $_POST["price"] == 0 || $_POST["price"] < 0) {
                return ["success" => false, "message" => "You need to add price for the membership or mark it as free."];
            }
            if ($_POST["chat_gpt_access"] == 1 && empty($_POST["chat_gpt_prompt_limit"])) {
                return ["success" => false, "message" => "Please add ChatGPT prompt limit."];
            }
            if (!empty($_POST["chat_gpt_prompt_limit"]) && $_POST["chat_gpt_prompt_limit"] < 0) {
                return ["success" => false, "message" => "Invalid ChatGPT prompt limit."];
            }
            if ($_POST["chat_gpt_access"] == 1 && $_POST["subscription_type"] == 1) {
                return ["success" => false, "message" => "You can't enable ChatGPT for free membership. It's not a good marketing practice."];
            }
            $this->model->addNewMembership(["membership_title" => $_POST["membership_title"], "subscription_type" => $_POST["subscription_type"], "sales_commission" => $_POST["sales_commission"], "clicks_commission" => $_POST["clicks_commission"], "timer_seconds" => $_POST["timer_seconds"], "email_sending_limit" => $_POST["email_sending_limit"], "price" => $_POST["price"], "bonus_email_credits" => $_POST["bonus_email_credits"], "bonus_text_ad_credits" => $_POST["bonus_text_ad_credits"], "bonus_banner_credits" => $_POST["bonus_banner_credits"], "credits_per_click" => $_POST["credits_per_click"], "max_recipient" => $_POST["max_recipient"], "status" => $_POST["status"], "hidden" => $_POST["hidden"], "chat_gpt_access" => $_POST["chat_gpt_access"], "chat_gpt_prompt_limit" => $_POST["chat_gpt_prompt_limit"], "stripe_price_id" => $_POST["stripe_price_id"]]);
            return ["success" => true, "message" => "Membership has been added."];
        }
    }
    public function updateMembership($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["membership_title"]) && isset($_POST["sales_commission"]) && isset($_POST["clicks_commission"]) && isset($_POST["timer_seconds"]) && isset($_POST["price"]) && isset($_POST["admin_csrf_token"]) && isset($_POST["subscription_type"]) && isset($_POST["bonus_email_credits"]) && isset($_POST["bonus_text_ad_credits"]) && isset($_POST["credits_per_click"]) && isset($_POST["bonus_banner_credits"]) && isset($_POST["max_recipient"]) && isset($_POST["hidden"]) && isset($_POST["chat_gpt_access"]) && isset($_POST["chat_gpt_prompt_limit"]) && isset($_POST["stripe_price_id"]) && isset($_POST["status"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["membership_title"]) || empty($_POST["timer_seconds"]) || empty($_POST["subscription_type"]) || empty($_POST["admin_csrf_token"]) || empty($_POST["credits_per_click"]) || empty($_POST["max_recipient"]) || empty($_POST["hidden"]) || empty($_POST["chat_gpt_access"]) || empty($_POST["status"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["timer_seconds"]) || $_POST["timer_seconds"] < 0) {
                return ["success" => false, "message" => "Invalid timer (seconds)."];
            }
            if (!is_numeric($_POST["max_recipient"]) || $_POST["max_recipient"] < 0) {
                return ["success" => false, "message" => "Invalid max recipient."];
            }
            if (!is_numeric($_POST["price"]) || $_POST["price"] < 0) {
                return ["success" => false, "message" => "Invalid price."];
            }
            if (!is_numeric($_POST["clicks_commission"]) || $_POST["clicks_commission"] < 0) {
                return ["success" => false, "message" => "Invalid click commission."];
            }
            if (!is_numeric($_POST["email_sending_limit"]) || $_POST["email_sending_limit"] < 0) {
                return ["success" => false, "message" => "Invalid daily email sending limit."];
            }
            if (!is_numeric($_POST["sales_commission"]) || $_POST["sales_commission"] < 0) {
                return ["success" => false, "message" => "Invalid sales commission."];
            }
            if (!is_numeric($_POST["bonus_email_credits"]) || $_POST["bonus_email_credits"] < 0) {
                return ["success" => false, "message" => "Invalid bonus email credits."];
            }
            if (!is_numeric($_POST["bonus_text_ad_credits"]) || $_POST["bonus_text_ad_credits"] < 0) {
                return ["success" => false, "message" => "Invalid bonus text ad credits."];
            }
            if (!is_numeric($_POST["bonus_banner_credits"]) || $_POST["bonus_banner_credits"] < 0) {
                return ["success" => false, "message" => "Invalid bonus banner ad credits."];
            }
            if (!is_numeric($_POST["credits_per_click"]) || $_POST["credits_per_click"] < 0) {
                return ["success" => false, "message" => "Invalid credits per click."];
            }
            if (!is_numeric($_POST["hidden"]) || $_POST["hidden"] < 0 || 2 < $_POST["hidden"]) {
                return ["success" => false, "message" => "Invalid hidden status."];
            }
            if ($_POST["status"] != 1 && $_POST["status"] != 2) {
                return ["success" => false, "message" => "Invalid value for status."];
            }
            if ($_POST["subscription_type"] != 1 && $_POST["subscription_type"] != 2 && $_POST["subscription_type"] != 3 && $_POST["subscription_type"] != 4) {
                return ["success" => false, "message" => "Invalid subscription type."];
            }
            if (255 < strlen($_POST["membership_title"])) {
                return ["success" => false, "message" => "Membership titile is too long."];
            }
            if ($_POST["subscription_type"] == 1 && 0 < $_POST["price"]) {
                return ["success" => false, "message" => "You can't add price for free membership."];
            }
            if ($_POST["subscription_type"] != 1 && $_POST["price"] == 0 || $_POST["price"] < 0) {
                return ["success" => false, "message" => "You need to add price for the membership or mark it as free."];
            }
            if ($_POST["chat_gpt_access"] == 1 && empty($_POST["chat_gpt_prompt_limit"])) {
                return ["success" => false, "message" => "Please add ChatGPT prompt limit."];
            }
            if (!empty($_POST["chat_gpt_prompt_limit"]) && $_POST["chat_gpt_prompt_limit"] < 0) {
                return ["success" => false, "message" => "Invalid ChatGPT prompt limit."];
            }
            if ($_POST["chat_gpt_access"] == 1 && $_POST["subscription_type"] == 1) {
                return ["success" => false, "message" => "You can't enable ChatGPT for free membership. It's not a good marketing practice."];
            }
            $this->model->updateMembership(["membership_title" => $_POST["membership_title"], "subscription_type" => $_POST["subscription_type"], "sales_commission" => $_POST["sales_commission"], "clicks_commission" => $_POST["clicks_commission"], "timer_seconds" => $_POST["timer_seconds"], "email_sending_limit" => $_POST["email_sending_limit"], "price" => $_POST["price"], "bonus_email_credits" => $_POST["bonus_email_credits"], "bonus_text_ad_credits" => $_POST["bonus_text_ad_credits"], "bonus_banner_credits" => $_POST["bonus_banner_credits"], "credits_per_click" => $_POST["credits_per_click"], "max_recipient" => $_POST["max_recipient"], "status" => $_POST["status"], "hidden" => $_POST["hidden"], "chat_gpt_access" => $_POST["chat_gpt_access"], "chat_gpt_prompt_limit" => $_POST["chat_gpt_prompt_limit"], "stripe_price_id" => $_POST["stripe_price_id"]], $id);
            return ["success" => true, "message" => "Membership has been updated."];
        }
    }
    public function updateDefaultMembership($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["membership_title"]) && isset($_POST["sales_commission"]) && isset($_POST["clicks_commission"]) && isset($_POST["timer_seconds"]) && isset($_POST["email_sending_limit"]) && isset($_POST["bonus_email_credits"]) && isset($_POST["bonus_text_ad_credits"]) && isset($_POST["bonus_banner_credits"]) && isset($_POST["credits_per_click"]) && isset($_POST["max_recipient"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["membership_title"]) || empty($_POST["timer_seconds"]) || empty($_POST["credits_per_click"]) || empty($_POST["max_recipient"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["timer_seconds"]) || $_POST["timer_seconds"] < 0) {
                return ["success" => false, "message" => "Invalid timer (seconds)."];
            }
            if (!is_numeric($_POST["clicks_commission"]) || $_POST["clicks_commission"] < 0) {
                return ["success" => false, "message" => "Invalid click commission."];
            }
            if (!is_numeric($_POST["sales_commission"]) || $_POST["sales_commission"] < 0) {
                return ["success" => false, "message" => "Invalid sales commission."];
            }
            if (!is_numeric($_POST["max_recipient"]) || $_POST["max_recipient"] < 0) {
                return ["success" => false, "message" => "Invalid max recipient."];
            }
            if (!is_numeric($_POST["email_sending_limit"]) || $_POST["email_sending_limit"] < 0) {
                return ["success" => false, "message" => "Invalid daily email sending limit."];
            }
            if (!is_numeric($_POST["bonus_email_credits"]) || $_POST["bonus_email_credits"] < 0) {
                return ["success" => false, "message" => "Invalid bonus email credits."];
            }
            if (!is_numeric($_POST["bonus_text_ad_credits"]) || $_POST["bonus_text_ad_credits"] < 0) {
                return ["success" => false, "message" => "Invalid bonus text ad credits."];
            }
            if (!is_numeric($_POST["credits_per_click"]) || $_POST["credits_per_click"] < 0) {
                return ["success" => false, "message" => "Invalid credits per click."];
            }
            if (!is_numeric($_POST["bonus_banner_credits"]) || $_POST["bonus_banner_credits"] < 0) {
                return ["success" => false, "message" => "Invalid bonus banner ad credits."];
            }
            if (255 < strlen($_POST["membership_title"])) {
                return ["success" => false, "message" => "Membership titile is too long."];
            }
            $this->model->updateMembership(["membership_title" => $_POST["membership_title"], "sales_commission" => $_POST["sales_commission"], "clicks_commission" => $_POST["clicks_commission"], "email_sending_limit" => $_POST["email_sending_limit"], "bonus_email_credits" => $_POST["bonus_email_credits"], "bonus_text_ad_credits" => $_POST["bonus_text_ad_credits"], "bonus_banner_credits" => $_POST["bonus_banner_credits"], "credits_per_click" => $_POST["credits_per_click"], "max_recipient" => $_POST["max_recipient"], "timer_seconds" => $_POST["timer_seconds"]], $id);
            return ["success" => true, "message" => "Default Membership has been updated."];
        }
    }
    public function getSubscriptionType()
    {
        return ["Free", "Monthly", "Yearly", "Lifetime"];
    }
    public function deleteMembership()
    {
        if (isset($_GET["delete"]) && isset($_GET["token"])) {
            $adminController = new AdminController();
            if (!empty($_GET["delete"]) && !empty($_GET["token"]) && is_numeric($_GET["delete"]) && $adminController->getAdminCSRFToken() == $_GET["token"]) {
                $membershipDetails = $this->getMembershipDetails($_GET["delete"]);
                if (empty($membershipDetails)) {
                    return ["success" => false, "message" => "Couldn't find the membership."];
                }
                $membersController = new MembersController();
                if ($_GET["delete"] == 1) {
                    return ["success" => false, "message" => "Sorry, you can't delete this membership. This is default membership."];
                }
                if (0 < $membersController->totalMemberByMembership($_GET["delete"])) {
                    return ["success" => false, "message" => "Sorry, you can't delete this membership. Some members have this membership."];
                }
                $this->model->deleteMembership($_GET["delete"]);
                return ["success" => true, "message" => "The membership has been deleted."];
            }
        }
    }
    public function getMembershipDetails($id)
    {
        return $this->model->getMembershipDetails($id);
    }
    public function membershipList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalMemberships();
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
        return $this->model->membershipsList(30, $offset);
    }
    public function getActiveMembershipsList()
    {
        return $this->model->activeMembershpsList(1000, 0);
    }
    public function totalMemberships()
    {
        return $this->model->totalMemberships();
    }
    public function membershipListPagination()
    {
        $this->pagination(30, $this->totalMemberships(), "memberships.php");
    }
    public function getTable()
    {
        return $this->model->getTable();
    }
    public function getAllMemberships()
    {
        return $this->model->membershipsList(1000, 0);
    }
}

?>