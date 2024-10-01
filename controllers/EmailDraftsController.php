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
class EmailDraftsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new EmailDraftsModel();
    }
    public function addDraft($username)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email_subject"]) && isset($_POST["email_body"]) && isset($_POST["website_link"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $membersController = new MembersController();
            if (empty($_POST["email_subject"]) || empty($_POST["email_body"]) || empty($_POST["website_link"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($membersController->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if (100 < strlen($_POST["email_subject"])) {
                return ["success" => false, "message" => "Email subject is too long."];
            }
            if (255 < strlen($_POST["website_link"])) {
                return ["success" => false, "message" => "Website link is too long."];
            }
            if (!filter_var($_POST["website_link"], FILTER_SANITIZE_URL)) {
                return ["success" => false, "message" => "Invalid website link."];
            }
            $this->model->addDraft(["email_subject" => $_POST["email_subject"], "email_body" => $_POST["email_body"], "website_link" => $_POST["website_link"], "username" => $username]);
            return ["success" => true, "message" => "Email has been saved."];
        }
    }
    public function updateDraft($username, $id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email_subject"]) && isset($_POST["email_body"]) && isset($_POST["website_link"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $membersController = new MembersController();
            $draftInfo = $this->getDraftInfo($id);
            if (empty($draftInfo) || $draftInfo["username"] != $username) {
                return ["success" => false, "message" => "Invalid draft or you dont' have permission to edit the draft."];
            }
            if (empty($_POST["email_subject"]) || empty($_POST["email_body"]) || empty($_POST["website_link"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($membersController->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if (100 < strlen($_POST["email_subject"])) {
                return ["success" => false, "message" => "Email subject is too long."];
            }
            if (255 < strlen($_POST["website_link"])) {
                return ["success" => false, "message" => "Website link is too long."];
            }
            if (!filter_var($_POST["website_link"], FILTER_SANITIZE_URL)) {
                return ["success" => false, "message" => "Invalid website link."];
            }
            $this->model->updateDraft(["email_subject" => $_POST["email_subject"], "email_body" => $_POST["email_body"], "website_link" => $_POST["website_link"]], $id);
            return ["success" => true, "message" => "Email has been updated."];
        }
    }
    public function getDraftInfo($id)
    {
        return $this->model->getDraftInfo($id);
    }
    public function totalDrafts($username)
    {
        return $this->model->totalDrafts($username);
    }
    public function darftList($username)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalDrafts($username);
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
        return $this->model->getAllDrafts($username, $offset, 30);
    }
    public function draftPagination($username)
    {
        $total = $this->totalDrafts($username);
        $limit = 30;
        $total_offset = ceil($total / $limit);
        $current_page = 1;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] <= $total_offset) {
            $current_page = $_GET["page"];
        }
        if (1 < $total_offset) {
            echo "<nav aria-label=\"Page navigation example\"><ul class=\"pagination\">";
            if (1 < $current_page) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"emails.php?action=saved&page=" . ($current_page - 1) . "\">Previous</a></li>";
            }
            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"#\">" . $current_page . "</a></li>";
            if ($current_page < $total_offset) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"emails.php?action=saved&page=" . ($current_page + 1) . "\">Next</a></li>";
            }
            echo "</ul></nav>";
        }
    }
    public function deleteDraft($username)
    {
        if (isset($_GET["delete"]) && isset($_GET["token"])) {
            if (empty($_GET["delete"]) || empty($_GET["token"])) {
                return ["success" => false, "message" => "Invalid delete request."];
            }
            if ($_GET["delete"] < 1) {
                return ["success" => false, "message" => "Invalid delete request."];
            }
            if (!is_numeric($_GET["delete"])) {
                return ["success" => false, "message" => "Invalid delete request."];
            }
            $membersController = new MembersController();
            if ($membersController->getUserCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid delete request."];
            }
            $draftInfo = $this->getDraftInfo($_GET["delete"]);
            if (empty($draftInfo) || $draftInfo["username"] != $username) {
                return ["success" => false, "message" => "Invalid draft email."];
            }
            $this->model->deleteDraft($_GET["delete"]);
            return ["success" => true, "message" => "Draft has been deleted."];
        }
    }
}

?>