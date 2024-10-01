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
class MassMailController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new MassMailModel();
    }
    public function addNewMail()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email_subject"]) && isset($_POST["email_body"]) && isset($_POST["membership"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["email_subject"]) || empty($_POST["email_body"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (100 < strlen($_POST["email_subject"])) {
                return ["success" => false, "message" => "Email subject is too long. You can add maximum 100 characters."];
            }
            if (!empty($_POST["membership"]) && !is_numeric($_POST["membership"])) {
                return ["success" => false, "message" => "Invalid membership."];
            }
            $membership = 0;
            if (0 < $_POST["membership"]) {
                $membership = $_POST["membership"];
                $membershipController = new MembershipsController();
                $membershipData = $membershipController->getMembershipDetails($_POST["membership"]);
                if (empty($membershipData)) {
                    return ["success" => false, "message" => "Invalid membership."];
                }
            }
            $this->model->addNewMail(["email_subject" => base64_encode($_POST["email_subject"]), "email_body" => base64_encode($_POST["email_body"]), "membership" => $membership, "email_timestamp" => time(), "status" => 0]);
            return ["success" => true, "message" => "Email has been added to queue."];
        }
    }
    public function totalMassMails()
    {
        return $this->model->totalMails();
    }
    public function massMailPagination()
    {
        return $this->pagination(30, $this->totalMassMails(), "mass-mail.php");
    }
    public function massMailList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalMassMails();
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
    public function getQueuedEmail()
    {
        return $this->model->getQueuedEmail();
    }
    public function updateMail($id, $data)
    {
        return $this->model->updateMail($data, $id);
    }
}

?>