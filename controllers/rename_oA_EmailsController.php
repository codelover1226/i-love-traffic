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
class EmailsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new EmailsModel();
    }
    public function totalMails()
    {
        return $this->model->totalMails();
    }
    public function totalUserEmails($username)
    {
        return $this->model->totalUserEmails($username);
    }
    public function emailListPagination()
    {
        return $this->pagination(30, $this->totalMails(), "email-list.php");
    }
    public function emailList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalMails();
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
        return $this->model->mailList(30, $offset);
    }
    public function userEmailList($username)
    {
        return $this->model->userEmailList($username, 30, 0);
    }
    public function userEmailPagination($username)
    {
        return $this->pagination(30, $this->totalUserEmails($username), "emails.php");
    }
    public function getMailDetails($id)
    {
        return $this->model->getMailDetails($id);
    }
    public function suspendMail()
    {
        if (isset($_GET["suspend"]) && !empty($_GET["suspend"]) && isset($_GET["token"]) && !empty($_GET["token"]) && is_numeric($_GET["suspend"])) {
            $adminController = new AdminController();
            if ($_GET["token"] == $adminController->getAdminCSRFToken()) {
                $emailDetails = $this->getMailDetails($_GET["suspend"]);
                if (empty($emailDetails)) {
                    return ["success" => false, "message" => "Couldn't find the email."];
                }
                $this->model->updateMail(["suspend_status" => 1], $_GET["suspend"]);
                return ["success" => true, "message" => "The email has been suspended."];
            }
        }
    }
    public function unsuspendMail()
    {
        if (isset($_GET["unsuspend"]) && !empty($_GET["unsuspend"]) && isset($_GET["token"]) && !empty($_GET["token"]) && is_numeric($_GET["unsuspend"])) {
            $adminController = new AdminController();
            if ($_GET["token"] == $adminController->getAdminCSRFToken()) {
                $emailDetails = $this->getMailDetails($_GET["unsuspend"]);
                if (empty($emailDetails)) {
                    return ["success" => false, "message" => "Couldn't find the email."];
                }
                $this->model->updateMail(["suspend_status" => 2], $_GET["unsuspend"]);
                return ["success" => true, "message" => "The email has been unsuspended."];
            }
        }
    }
    public function emailStatus()
    {
        return ["In Queue", "Sending", "Sent", "Scheduled", "Cancelled"];
    }
    public function getTable()
    {
        return $this->model->getTable();
    }
    public function totalEmailSentToday()
    {
        return $this->model->totalSentToday()["total_sent"];
    }
    public function totalEmailSent()
    {
        return $this->model->totalEmailSent()["total_sent"];
    }
    public function totalEmailClicks()
    {
        return $this->model->totalEmailClicks()["total_email_clicks"];
    }
    public function totalUserEmailsToday($username)
    {
        return $this->model->totalUserEmailsToday($username);
    }
    public function checkNewEmailWebsite($userInfo, $maxRecipient)
    {
        if (isset($_POST["email_subject"]) && isset($_POST["email_body"]) && isset($_POST["website_link"]) && isset($_POST["credits_assign"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $membersController = new MembersController();
            if (empty($_POST["email_subject"]) || empty($_POST["email_body"]) || empty($_POST["website_link"]) || empty($_POST["credits_assign"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($membersController->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (100 < strlen($_POST["email_subject"])) {
                return ["success" => false, "message" => "Email subject is too long. You can enter maximum 100 characters."];
            }
            if (!is_numeric($_POST["credits_assign"]) || $_POST["credits_assign"] < 1) {
                return ["success" => false, "message" => "Invalid credits."];
            }
            if ($userInfo["credits"] < $_POST["credits_assign"]) {
                return ["success" => false, "message" => "You don't have enough credits."];
            }
            if ($maxRecipient - 1 < $_POST["credits_assign"]) {
                return ["success" => false, "message" => "You can add maximum " . ($maxRecipient - 1) . " credits"];
            }
            if (!filter_var($_POST["website_link"], FILTER_VALIDATE_URL)) {
                return ["success" => false, "message" => "Invalid website link."];
            }
            if ($userInfo["account_status"] != 1) {
                return ["success" => false, "message" => "You are not subscribed to our emails. You can't send email."];
            }
            if ($userInfo["email_sending_limit"] <= $this->totalUserEmailsToday($userInfo["username"])) {
                return ["success" => false, "message" => "You have reached your email sending limit."];
            }
            $membersController->generateUserCSRFToken();
            $bannedDomainController = new BannedDomainsController();
            if ($bannedDomainController->bannedDoomainDetails(parse_url($_POST["website_link"])["host"])) {
                return ["success" => false, "message" => "The website is in our banned list."];
            }
            echo "<form method=\"POST\" action=\"website-check.php\" id=\"website_check_form\">";
            echo "<input type=\"hidden\" name=\"email_subject\" value=\"" . $_POST["email_subject"] . "\">";
            echo "<input type=\"hidden\" name=\"email_body\" value=\"" . base64_encode(htmlspecialchars(stripslashes($_POST["email_body"]), ENT_QUOTES, "UTF-8")) . "\">";
            echo "<input type=\"hidden\" name=\"credits_assign\" value=\"" . $_POST["credits_assign"] . "\">";
            echo "<input type=\"hidden\" name=\"website_link\" value=\"" . $_POST["website_link"] . "\">";
            echo "<input type=\"hidden\" name=\"csrf_token\" value=\"" . $membersController->getUserCSRFToken() . "\">";
            echo "</form><script>document.getElementById(\"website_check_form\").submit();</script>";
        }
    }
    public function addMailInQueue($userInfo, $maxRecipient)
    {
        if (isset($_POST["email_subject"]) && isset($_POST["add_queue"]) && isset($_POST["email_body"]) && isset($_POST["website_link"]) && isset($_POST["credits_assign"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $membersController = new MembersController();
            if (empty($_POST["email_subject"]) || empty($_POST["email_body"]) || empty($_POST["website_link"]) || empty($_POST["credits_assign"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($membersController->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (100 < strlen($_POST["email_subject"])) {
                return ["success" => false, "message" => "Email subject is too long. You can enter maximum 100 characters."];
            }
            if (!is_numeric($_POST["credits_assign"]) || $_POST["credits_assign"] < 1) {
                return ["success" => false, "message" => "Invalid credits."];
            }
            if ($userInfo["credits"] < $_POST["credits_assign"]) {
                return ["success" => false, "message" => "You don't have enough credits."];
            }
            if ($maxRecipient - 1 < $_POST["credits_assign"]) {
                return ["success" => false, "message" => "You can add maximum " . ($maxRecipient - 1) . " credits"];
            }
            if (!filter_var($_POST["website_link"], FILTER_VALIDATE_URL)) {
                return ["success" => false, "message" => "Invalid website link."];
            }
            if ($userInfo["account_status"] != 1) {
                return ["success" => false, "message" => "You are not subscribed to our emails. You can send email."];
            }
            if ($userInfo["email_sending_limit"] <= $this->totalUserEmailsToday($userInfo["username"])) {
                return ["success" => false, "message" => "You have reached your email sending limit."];
            }
            $bannedDomainController = new BannedDomainsController();
            if ($bannedDomainController->bannedDoomainDetails(parse_url($_POST["website_link"])["host"])) {
                return ["success" => false, "message" => "The website is in our banned list."];
            }
            $this->model->addNewMail(["sender_username" => $userInfo["username"], "email_subject" => base64_encode($_POST["email_subject"]), "email_body" => $_POST["email_body"], "website_link" => base64_encode($_POST["website_link"]), "sending_time" => time(), "total_sent" => 0, "creation_timestamp" => time(), "total_clicks" => 0, "credits_assign" => $_POST["credits_assign"], "credit_key" => md5(uniqid("NTKS")), "email_status" => 0, "suspend_status" => 0]);
            $membersController->deductMemberCredits($userInfo["username"], $_POST["credits_assign"]);
            return ["success" => true, "message" => "Email has been added to queue."];
        }
    }
    public function checkNewSchedulEmailWebsite($userInfo, $maxRecipient)
    {
        if (isset($_POST["email_subject"]) && isset($_POST["email_body"]) && isset($_POST["website_link"]) && isset($_POST["credits_assign"]) && isset($_POST["schedule_date"]) && isset($_POST["schedule_hour"]) && isset($_POST["schedule_minute"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $membersController = new MembersController();
            if (empty($_POST["email_subject"]) || empty($_POST["email_body"]) || empty($_POST["website_link"]) || empty($_POST["credits_assign"]) || empty($_POST["schedule_date"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($membersController->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (100 < strlen($_POST["email_subject"])) {
                return ["success" => false, "message" => "Email subject is too long. You can enter maximum 100 characters."];
            }
            if (!is_numeric($_POST["credits_assign"]) || $_POST["credits_assign"] < 1) {
                return ["success" => false, "message" => "Invalid credits."];
            }
            if ($userInfo["credits"] < $_POST["credits_assign"]) {
                return ["success" => false, "message" => "You don't have enough credits."];
            }
            if ($maxRecipient - 1 < $_POST["credits_assign"]) {
                return ["success" => false, "message" => "You can add maximum " . ($maxRecipient - 1) . " credits"];
            }
            if (!filter_var($_POST["website_link"], FILTER_VALIDATE_URL)) {
                return ["success" => false, "message" => "Invalid website link."];
            }
            if ($userInfo["account_status"] != 1) {
                return ["success" => false, "message" => "You are not subscribed to our emails. You can send email."];
            }
            if ($userInfo["email_sending_limit"] <= $this->totalUserEmailsToday($userInfo["username"])) {
                return ["success" => false, "message" => "You have reached your email sending limit."];
            }
            $dateArray = explode("-", $_POST["schedule_date"]);
            if (count($dateArray) != 3 || !checkdate($dateArray[1], $dateArray[2], $dateArray[0])) {
                return ["success" => false, "message" => "Invalid date."];
            }
            if (!is_numeric($_POST["schedule_hour"]) || $_POST["schedule_hour"] < 0 || 23 < $_POST["shedule_hour"]) {
                return ["success" => false, "message" => "Invalid hour."];
            }
            if (!is_numeric($_POST["schedule_minute"]) || $_POST["schedule_minute"] < 0 || 55 < $_POST["schedule_minute"] || $_POST["schedule_minute"] % 5 != 0) {
                return ["success" => false, "message" => "Invalid minute."];
            }
            $membersController->generateUserCSRFToken();
            $bannedDomainController = new BannedDomainsController();
            if ($bannedDomainController->bannedDoomainDetails(parse_url($_POST["website_link"])["host"])) {
                return ["success" => false, "message" => "The website is in our banned list."];
            }
            echo "<form method=\"POST\" action=\"website-check.php\" id=\"website_check_form\">";
            echo "<input type=\"hidden\" name=\"email_subject\" value=\"" . $_POST["email_subject"] . "\">";
            echo "<input type=\"hidden\" name=\"email_body\" value=\"" . base64_encode(htmlspecialchars(stripslashes($_POST["email_body"]), ENT_QUOTES, "UTF-8")) . "\">";
            echo "<input type=\"hidden\" name=\"credits_assign\" value=\"" . $_POST["credits_assign"] . "\">";
            echo "<input type=\"hidden\" name=\"website_link\" value=\"" . $_POST["website_link"] . "\">";
            echo "<input type=\"hidden\" name=\"schedule_date\" value=\"" . $_POST["schedule_date"] . "\">";
            echo "<input type=\"hidden\" name=\"schedule_hour\" value=\"" . $_POST["schedule_hour"] . "\">";
            echo "<input type=\"hidden\" name=\"schedule_minute\" value=\"" . $_POST["schedule_minute"] . "\">";
            echo "<input type=\"hidden\" name=\"csrf_token\" value=\"" . $membersController->getUserCSRFToken() . "\">";
            echo "</form><script>document.getElementById(\"website_check_form\").submit();</script>";
        }
    }
    public function schedulEmail($userInfo, $maxRecipient)
    {
        if (isset($_POST["email_subject"]) && isset($_POST["email_body"]) && isset($_POST["website_link"]) && isset($_POST["credits_assign"]) && isset($_POST["schedule_date"]) && isset($_POST["schedule_hour"]) && isset($_POST["schedule_minute"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $membersController = new MembersController();
            if (empty($_POST["email_subject"]) || empty($_POST["email_body"]) || empty($_POST["website_link"]) || empty($_POST["credits_assign"]) || empty($_POST["schedule_date"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($membersController->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (100 < strlen($_POST["email_subject"])) {
                return ["success" => false, "message" => "Email subject is too long. You can enter maximum 100 characters."];
            }
            if (!is_numeric($_POST["credits_assign"]) || $_POST["credits_assign"] < 1) {
                return ["success" => false, "message" => "Invalid credits."];
            }
            if ($userInfo["credits"] < $_POST["credits_assign"]) {
                return ["success" => false, "message" => "You don't have enough credits."];
            }
            if ($maxRecipient - 1 < $_POST["credits_assign"]) {
                return ["success" => false, "message" => "You can add maximum " . ($maxRecipient - 1) . " credits"];
            }
            if (!filter_var($_POST["website_link"], FILTER_VALIDATE_URL)) {
                return ["success" => false, "message" => "Invalid website link."];
            }
            if ($userInfo["account_status"] != 1) {
                return ["success" => false, "message" => "You are not subscribed to our emails. You can send email."];
            }
            if ($userInfo["email_sending_limit"] <= $this->totalUserEmailsToday($userInfo["username"])) {
                return ["success" => false, "message" => "You have reached your email sending limit."];
            }
            $dateArray = explode("-", $_POST["schedule_date"]);
            if (count($dateArray) != 3 || !checkdate($dateArray[1], $dateArray[2], $dateArray[0])) {
                return ["success" => false, "message" => "Invalid date."];
            }
            if (!is_numeric($_POST["schedule_hour"]) || $_POST["schedule_hour"] < 0 || 23 < $_POST["schedule_hour"]) {
                return ["success" => false, "message" => "Invalid hour."];
            }
            if (!is_numeric($_POST["schedule_minute"]) || $_POST["schedule_minute"] < 0 || 55 < $_POST["schedule_minute"] || $_POST["schedule_minute"] % 5 != 0) {
                return ["success" => false, "message" => "Invalid minute."];
            }
            $membersController->generateUserCSRFToken();
            $bannedDomainController = new BannedDomainsController();
            if ($bannedDomainController->bannedDoomainDetails(parse_url($_POST["website_link"])["host"])) {
                return ["success" => false, "message" => "The website is in our banned list."];
            }
            $scheduleTime = strtotime($_POST["schedule_date"] . " " . $_POST["schedule_hour"] . ":" . $_POST["schedule_minute"] . ":00");
            if ($scheduleTime < time()) {
                return ["success" => false, "message" => "You can't send email in past time."];
            }
            $this->model->addNewMail(["sender_username" => $userInfo["username"], "email_subject" => base64_encode($_POST["email_subject"]), "email_body" => $_POST["email_body"], "website_link" => base64_encode($_POST["website_link"]), "sending_time" => $scheduleTime, "total_sent" => 0, "creation_timestamp" => time(), "total_clicks" => 0, "credits_assign" => $_POST["credits_assign"], "credit_key" => md5(uniqid("NTKS")), "email_status" => 3, "suspend_status" => 0]);
            $membersController->deductMemberCredits($userInfo["username"], $_POST["credits_assign"]);
            return ["success" => true, "message" => "Email has been scheduled."];
        }
    }
    public function getMailDetailsByCreditKey($creditKey)
    {
        return $this->model->getMailDetailsByCreditKey($creditKey);
    }
    public function addEmailClicks($id)
    {
        return $this->model->addEmailClicks($id);
    }
    public function updateEmailStatus($id, $status)
    {
        return $this->model->updateEmailStatus($id, ["email_status" => $status]);
    }
    public function getEmailsForSending()
    {
        return $this->model->getEmailsForSending();
    }
    public function updateEmailData($id, $data)
    {
        return $this->model->updateMail($data, $id);
    }
    public function getScheduleEmailsForSending()
    {
        return $this->model->getScheduleEmailForSending();
    }
    public function insertEmail($data)
    {
        return $this->model->addNewMail($data);
    }
}

?>