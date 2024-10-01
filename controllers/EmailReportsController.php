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
class EmailReportsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new EmailReportsModel();
    }
    public function emailReportsList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalReports();
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
        return $this->model->emailReportList(30, $offset);
    }
    public function emailReportsPagination()
    {
        return $this->pagination(30, $this->totalReports(), "email-reports.php");
    }
    public function checkUserEmailReport($reportSender, $emailId)
    {
        return $this->model->checkUserEmailReport($reportSender, $emailId);
    }
    public function totalReports()
    {
        return $this->model->totalReports();
    }
    public function reportStatus()
    {
        return ["Unread", "Solved"];
    }
    public function markAsSolved()
    {
        if (isset($_GET["solved"]) && isset($_GET["token"]) && !empty($_GET["solved"]) && !empty($_GET["token"]) && is_numeric($_GET["solved"])) {
            $adminController = new AdminController();
            if ($_GET["token"] == $adminController->getAdminCSRFToken()) {
                $this->model->updateReport(["report_status" => 2], $_GET["solved"]);
            }
        }
    }
    public function recentEmailReports()
    {
        return $this->model->emailReportList(5, 0);
    }
    public function reportEmail()
    {
        if (isset($_GET["id"]) && isset($_GET["report-key"]) && isset($_GET["username"]) && !empty($_GET["id"]) && !empty($_GET["report-key"]) && !empty($_GET["username"]) && is_numeric($_GET["id"]) && 0 < $_GET["id"]) {
            $membersController = new MembersController();
            $memberDetails = $membersController->getUserDetails($_GET["username"]);
            if (!empty($memberDetails)) {
                $emailsController = new EmailsController();
                $emailDetails = $emailsController->getMailDetails($_GET["id"]);
                if (empty($emailDetails)) {
                    return ["success" => false, "message" => "Invalid email."];
                }
                if ($memberDetails["account_status"] == 1) {
                    if ($memberDetails["email_report_key"] == $_GET["report-key"]) {
                        if (0 < $this->checkUserEmailReport($_GET["username"], $emailDetails["id"])) {
                            return ["success" => false, "message" => "You have already reported about this email."];
                        }
                        $this->model->addNewReport(["email_id" => $_GET["id"], "report_sender" => $_GET["username"], "email_sender" => $emailDetails["sender_username"], "report_status" => 1, "report_timestamp" => time()]);
                        return ["success" => true, "message" => "Thanks for your report. We will take action soon."];
                    }
                    return ["suceess" => false, "message" => "Invalie report key."];
                }
                return ["success" => false, "message" => "Your account is not active or unsubscribed from our emails. So you can not report."];
            }
        }
    }
}

?>