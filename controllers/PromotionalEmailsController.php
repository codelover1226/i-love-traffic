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
class PromotionalEmailsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new PromotionalEmailsModel();
    }
    public function AddPromotionalEmails()
    {
        if (isset($_POST["admin_csrf_token"]) && isset($_POST["email_subject"]) && isset($_POST["email_body"])) {
            $admin_csrf_token = $_POST["admin_csrf_token"];
            $email_subject = $_POST["email_subject"];
            $email_body = $_POST["email_body"];
            if (empty($admin_csrf_token) || empty($email_subject) || empty($email_body)) {
                return ["success" => "false", "message" => "All fields are required"];
            }
            if (5000 <= strlen($email_body)) {
                return ["success" => "false", "message" => "Email body is too long"];
            }
            if (200 <= strlen($email_subject)) {
                return ["success" => "false", "message" => "Email subject is too long"];
            }
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $admin_csrf_token) {
                return ["success" => "false", "message" => "Invalid CSRF token"];
            }
            $this->model->addEmail(["email_subject" => base64_encode($email_subject), "email_body" => base64_encode($email_body)]);
            return ["success" => "true", "message" => "Email added successfully"];
        }
    }
    public function getEmails()
    {
        return $this->model->getEmails();
    }
    public function getEmailDetails($id)
    {
        return $this->model->getEmailDetails($id);
    }
    public function deleteEmail()
    {
        if (isset($_GET["delete"]) && isset($_GET["token"]) && !empty($_GET["delete"]) && !empty($_GET["token"])) {
            $delete = $_GET["delete"];
            $token = $_GET["token"];
            if (is_numeric($delete)) {
                $adminController = new AdminController();
                if ($adminController->getAdminCSRFToken() != $token) {
                    return ["success" => "false", "message" => "Invalid CSRF token"];
                }
                $this->model->deleteEmail($delete);
                return ["success" => "true", "message" => "Email deleted successfully"];
            }
        }
    }
    public function updateEmail($id)
    {
        if (isset($_POST["admin_csrf_token"]) && isset($_POST["email_subject"]) && isset($_POST["email_body"])) {
            $admin_csrf_token = $_POST["admin_csrf_token"];
            $email_subject = $_POST["email_subject"];
            $email_body = $_POST["email_body"];
            if (empty($admin_csrf_token) || empty($email_subject) || empty($email_body)) {
                return ["success" => "false", "message" => "All fields are required"];
            }
            if (5000 <= strlen($email_body)) {
                return ["success" => "false", "message" => "Email body is too long"];
            }
            if (200 <= strlen($email_subject)) {
                return ["success" => "false", "message" => "Email subject is too long"];
            }
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $admin_csrf_token) {
                return ["success" => "false", "message" => "Invalid CSRF token"];
            }
            $this->model->updateEmail($id, ["email_subject" => base64_encode($email_subject), "email_body" => base64_encode($email_body)]);
            return ["success" => "true", "message" => "Email updated successfully"];
        }
    }
}

?>