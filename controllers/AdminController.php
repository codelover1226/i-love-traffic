<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    header("Location: / ");
    exit;
}
require_once "../vendor/autoload.php";
if (file_exists("load_classes.php")) {
    require_once "load_classes.php";
} else {
    if (file_exists("../load_classes.php")) {
        require_once "../load_classes.php";
    }
}
class AdminController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new AdminModel();
    }
    public function getAdminDetails($username)
    {
        return $this->model->getAdminDetails($username);
    }
    public function login()
    {
        if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["admin_login_csrf"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["admin_login_csrf"])) {
                return ["success" => false, "message" => "Please enter your username and password."];
            }
            if ($_SESSION["admin_login_csrf"] != $_POST["admin_login_csrf"]) {
                return ["success" => false, "message" => "Invalid login request."];
            }
            $admin_details = $this->getAdminDetails($_POST["username"]);
            if (empty($admin_details)) {
                return ["success" => false, "message" => "Wrong credentials !"];
            }
            if (md5($_POST["password"]) == $admin_details["password"]) {
                $login_token = md5(uniqid("ntk" . $_POST["username"]));
                $this->model->createToken($_POST["username"], $login_token);
                $_SESSION["token"] = $login_token;
                $_SESSION["admin_username"] = $_POST["username"];
                $_SESSION["admin_csrf"] = md5(uniqid("ntk+s"));
                header("Location: index.php");
            } else {
                return ["success" => false, "message" => "Wrong credentials !"];
            }
        }
    }
    public function logout()
    {
        if (isset($_GET["logout"]) && !empty($_GET["logout"]) && isset($_SESSION["admin_csrf"]) && md5($_SESSION["admin_csrf"]) == $_GET["logout"]) {
            $this->model->deleteToken($_SESSION["admin_username"]);
            unset($_SESSION["token"]);
            unset($_SESSION["admin_csrf"]);
            unset($_SESSION["admin_username"]);
            header("Location: login.php");
            exit;
        }
    }
    public function verifyLogin($arg)
    {
        if (isset($_SESSION["token"])) {
            if ($this->model->countToken($_SESSION["token"]) == 1) {
                if ($arg == "login") {
                    header("Location: index.php");
                    exit;
                }
            } else {
                if ($arg != "login") {
                    header("Location: login.php");
                    exit;
                }
            }
        } else {
            if ($arg != "login") {
                header("Location: login.php");
                exit;
            }
        }
    }
    public function initSession()
    {
        if (session_id() == "") {
            session_start();
            $_SESSION["user_access"] = false;
        } else {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $_SESSION["user_access"] = false;
            }
        }
    }
    public function changeAdminPassword()
    {
        $adminInfo = $this->getAdminDetails($_SESSION["admin_username"]);
        if (isset($_POST["password"]) && isset($_POST["newpassword"]) && isset($_POST["confirmnewpassword"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            if (empty($_POST["password"]) || empty($_POST["newpassword"]) || empty($_POST["confirmnewpassword"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $this->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (strlen($_POST["newpassword"]) <= 8) {
                return ["success" => false, "message" => "New password must be more than 8 characters."];
            }
            if ($_POST["confirmnewpassword"] != $_POST["newpassword"]) {
                return ["success" => false, "message" => "New password didn't match."];
            }
            if ($adminInfo["password"] != md5($_POST["password"])) {
                return ["success" => false, "message" => "Current password didn't match."];
            }
            $this->model->updateAdminDetails($_SESSION["admin_username"], ["password" => md5($_POST["newpassword"])]);
            return ["success" => true, "message" => "Password has been changed."];
        }
    }
    public function changeAdminEmail()
    {
        $adminInfo = $this->getAdminDetails($_SESSION["admin_username"]);
        if (isset($_POST["password"]) && isset($_POST["email"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            if (empty($_POST["password"]) || empty($_POST["email"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $this->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if ($adminInfo["password"] != md5($_POST["password"])) {
                return ["success" => false, "message" => "Current password didn't match."];
            }
            $this->model->updateAdminDetails($_SESSION["admin_username"], ["email" => $_POST["email"]]);
            return ["success" => true, "message" => "Email has been changed."];
        }
    }
    public function adminCSRFTokenGen()
    {
        $_SESSION["admin_csrf_token"] = md5(uniqid("nTk+S"));
    }
    public function getAdminCSRFToken()
    {
        return $_SESSION["admin_csrf_token"];
    }
    public function sendSingleMail()
    {
        if (isset($_POST["email_subject"]) && isset($_POST["email_body"]) && isset($_POST["email_to"]) && isset($_POST["admin_csrf_token"])) {
            if (empty($_POST["email_subject"]) || empty($_POST["email_body"]) || empty($_POST["email_to"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if ($_POST["admin_csrf_token"] != $this->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!filter_var($_POST["email_to"])) {
                return ["success" => false, "message" => "Invalid email address."];
            }
            $siteSettingsController = new SiteSettingsController();
            $siteSettings = $siteSettingsController->getSettings();
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = SMTP_PROTOCOL;
            $mail->Port = SMTP_PORT;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->setFrom($siteSettings["admin_email"], $siteSettings["site_title"]);
            $mail->addReplyTo($siteSettings["admin_email"], $siteSettings["site_title"]);
            $mail->Subject = $_POST["email_subject"];
            $mail->msgHTML($_POST["email_body"]);
            $mail->addAddress($_POST["email_to"], "");
            $mail->send();
            $mail->clearAddresses();
            return ["success" => true, "message" => "Email has been sent."];
        }
    }
}

?>