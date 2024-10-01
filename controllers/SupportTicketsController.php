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
class SupportTicketsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new SupportTicketsModel();
    }
    public function getTicketDetails($id)
    {
        return $this->model->getTicketDetails($id);
    }
    public function totalAwaitingReplyTickets()
    {
        return $this->model->totalTicketsByStatus(1);
    }
    public function totalTickets()
    {
        return $this->model->totalTickets();
    }
    public function ticketList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalTickets();
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
        return $this->model->getAllTickets(30, $offset);
    }
    public function ticketListByStatus($status)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalTicketsByStatus($status);
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
        return $this->model->getAllTicketsByStatus(30, $offset, $status);
    }
    public function awatingTicketsPagination()
    {
        $this->pagination(30, $this->totalAwaitingReplyTickets(), "awating-reply-tickets.php");
    }
    public function totalTicketsByStatus($status)
    {
        return $this->model->totalTicketsByStatus($status);
    }
    public function ticketPagination()
    {
        return $this->pagination(30, $this->totalTickets(), "support-tickets.php");
    }
    public function getUserTicketDetails($id, $username)
    {
        $ticketDetails = $this->getTicketDetails($id);
        if ($ticketDetails["ticket_author_username"] == $username) {
            return $ticketDetails;
        }
        return "";
    }
    public function getTicketReplies($id)
    {
        return $this->model->getTicketReplies($id);
    }
    public function getUserReplies($id, $username)
    {
        $ticketDetails = $this->getTicketDetails($id);
        if ($ticketDetails["ticket_author_username"] == $username) {
            return $this->model->getTicketReplies($id);
        }
    }
    public function ticketStatus($arg)
    {
        if (0 < $arg) {
            return ["Awaiting Reply", "Admin Reply", "Closed"][$arg - 1];
        }
    }
    public function createTicket($userDetails, $siteSettigsData)
    {
        if (isset($_POST["ticket_title"]) && isset($_POST["ticket_body"]) && isset($_POST["csrf_token"])) {
            if (empty($_POST["ticket_title"]) || empty($_POST["ticket_body"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $membersController = new MembersController();
            if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (150 <= strlen($_POST["ticket_title"])) {
                return ["success" => false, "message" => "Ticket title is too long."];
            }
            if (1000 <= strlen($_POST["ticket_body"])) {
                return ["success" => false, "message" => "Ticket body is too long."];
            }
            $this->model->insertTicket(["ticket_author_username" => $userDetails["username"], "ticket_title" => $_POST["ticket_title"], "ticket_body" => $_POST["ticket_body"], "ticket_status" => 1, "ticket_timestamp" => time()]);
            $message = "Dear admin<br>";
            $message .= $userDetails["username"] . " has opened a support ticket.<br>";
            $message .= "Subject : ";
            $message .= $this->model->filter($_POST["ticket_title"]);
            $message .= "Ticket Message : <br>";
            $message .= nl2br($this->model->filter($_POST["ticket_body"]));
            SingleEmailSystem::sendEmail($userDetails["email"], $userDetails["first_name"], $siteSettigsData["admin_email"], $siteSettigsData["site_title"] . " Admin", "Support Ticket Opened : " . $this->model->filter($_POST["ticket_title"]), $message);
            return ["success" => true, "message" => "Ticket has been created."];
        }
    }
    public function totalUserTickets($username)
    {
        return $this->model->totalUserTickets($username);
    }
    public function totalUserTicketsByStatus($username, $status)
    {
        return $this->model->totalUserTicketByStatus($username, $status);
    }
    public function userTicketList($username)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalUserTickets($username);
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
        return $this->model->getUserTicketsList(30, $offset, $username);
    }
    public function userTicketListByStatus($username, $status)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalUserTicketsByStatus($username, $status);
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
        return $this->model->getUserTicketsListByStatus(30, $offset, $username, $status);
    }
    public function userTicketPagination($username)
    {
        $total = $this->totalUserTickets($username);
        $limit = 30;
        $this->pagination($limit, $total, "support-tickets.php");
    }
    public function supportTicketPagination()
    {
        $total = $this->totalTickets();
        $limit = 30;
        $this->pagination($limit, $total, "support-tickets.php");
    }
    public function createUserReply($userDetails, $siteSettigsData)
    {
        if (isset($_POST["ticket_id"]) && isset($_POST["reply"]) && isset($_POST["csrf_token"])) {
            if (empty($_POST["ticket_id"]) || empty($_POST["reply"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $membersController = new MembersController();
            if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["ticket_id"])) {
                return ["success" => false, "message" => "Invalid ticket id."];
            }
            if (1000 <= strlen($_POST["reply"])) {
                return ["success" => false, "message" => "Ticket reply is too long."];
            }
            $this->model->insertReply(["ticket_id" => $_POST["ticket_id"], "reply_author" => $userDetails["username"], "reply" => $_POST["reply"], "reply_timestamp" => time()]);
            $message = "Dear admin<br>";
            $message .= $userDetails["username"] . " has replied a ticket.<br>";
            $message .= "Subject : ";
            $message .= $this->model->filter($_POST["ticket_title"]);
            $message .= "Reply Message : <br>";
            $message .= nl2br($this->model->filter($_POST["reply"]));
            SingleEmailSystem::sendEmail($userDetails["email"], $userDetails["first_name"], $siteSettigsData["admin_email"], $siteSettigsData["site_title"] . " Admin", "Support Ticket Reply : " . $this->model->filter($_POST["ticket_title"]), $message);
            return ["success" => true, "message" => "Reply has been sent."];
        }
    }
    public function createReply($userDetails, $siteSettigsData)
    {
        if (isset($_POST["ticket_id"]) && isset($_POST["reply"]) && isset($_POST["csrf_token"])) {
            if (empty($_POST["ticket_id"]) || empty($_POST["reply"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $adminController = new AdminController();
            if ($_POST["csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["ticket_id"])) {
                return ["success" => false, "message" => "Invalid ticket id."];
            }
            if (1000 <= strlen($_POST["reply"])) {
                return ["success" => false, "message" => "Ticket reply is too long."];
            }
            $this->model->insertReply(["ticket_id" => $_POST["ticket_id"], "reply_author" => "admin", "reply" => $_POST["reply"], "reply_timestamp" => time()]);
            $message = "Dear ";
            $message .= $userDetails["username"] . "<br>";
            $message .= "An admin has replied to your ticket : ";
            $message .= $this->model->filter($_POST["ticket_title"]);
            $message .= "<br>Reply Message : <br>";
            $message .= nl2br($this->model->filter($_POST["reply"]));
            SingleEmailSystem::sendEmail("noreply@" . parse_url($siteSettigsData["installation_url"])["host"], $siteSettigsData["site_title"], $userDetails["email"], $userDetails["first_name"], "Support Ticket Reply : " . $this->model->filter($_POST["ticket_title"]), $message);
            return ["success" => true, "message" => "Reply has been sent."];
        }
    }
    public function userCloseTicket($username)
    {
        if (isset($_GET["close"]) && isset($_GET["token"]) && !empty($_GET["close"]) && !empty($_GET["token"])) {
            $membersController = new MembersController();
            if ($membersController->getUserCSRFToken() == $_GET["token"] && is_numeric($_GET["close"])) {
                $ticketDetails = $this->getTicketDetails($_GET["close"]);
                if ($ticketDetails["ticket_author_username"] == $username) {
                    $this->model->updateTicket($_GET["close"], ["ticket_status" => 3]);
                    return ["success" => true, "message" => "Ticket has been closed."];
                }
            }
        }
    }
    public function closeTicket()
    {
        if (isset($_GET["close"]) && isset($_GET["token"]) && !empty($_GET["close"]) && !empty($_GET["token"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() == $_GET["token"] && is_numeric($_GET["close"])) {
                $this->model->updateTicket($_GET["close"], ["ticket_status" => 3]);
                return ["success" => true, "message" => "Ticket has been closed."];
            }
        }
    }
    public function userOpenTicket($username)
    {
        if (isset($_GET["open"]) && isset($_GET["token"]) && !empty($_GET["open"]) && !empty($_GET["token"])) {
            $membersController = new MembersController();
            if ($membersController->getUserCSRFToken() == $_GET["token"] && is_numeric($_GET["open"])) {
                $ticketDetails = $this->getTicketDetails($_GET["open"]);
                if ($ticketDetails["ticket_author_username"] == $username) {
                    $this->model->updateTicket($_GET["open"], ["ticket_status" => 1]);
                    return ["success" => true, "message" => "Ticket has been opened."];
                }
            }
        }
    }
    public function openTicket()
    {
        if (isset($_GET["open"]) && isset($_GET["token"]) && !empty($_GET["open"]) && !empty($_GET["token"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() == $_GET["token"] && is_numeric($_GET["open"])) {
                $this->model->updateTicket($_GET["open"], ["ticket_status" => 1]);
                return ["success" => true, "message" => "Ticket has been opened."];
            }
        }
    }
}

?>