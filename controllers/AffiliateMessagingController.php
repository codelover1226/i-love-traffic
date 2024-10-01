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
class AffiliateMessagingController extends Controller
{
    private $model;
    public $maxMessageLimit = 300;
    public function __construct()
    {
        $this->model = new AffiliateMessagingModel();
    }
    public function totalAffiliateInboxMessage($username)
    {
        return $this->model->totalAffiliateInboxMessage($username);
    }
    public function totalAffiliateSentMessage($username)
    {
        return $this->model->totalAffiliateSentMessage($username);
    }
    public function affiliateInboxList($username)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalAffiliateInboxMessage($username);
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
        return $this->model->affilaiteInboxList(30, $offset, $username);
    }
    public function affiliateSentList($username)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalAffiliateSentMessage($username);
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
        return $this->model->affiliateSentList(30, $offset, $username);
    }
    public function affiliateInboxPagination($username)
    {
        $this->pagination(30, $this->totalAffiliateInboxMessage($username), "affiliate-inbox.php");
    }
    public function affiliateSentPagination($username)
    {
        $this->pagination(30, $this->totalAffiliateInboxMessage($username), "affiliate-sent.php");
    }
    public function markMessageRead($username, $id)
    {
        $messageDetails = $this->getMessageDetails($id);
        if (strtolower($messageDetails["receiver_username"]) == strtolower($username) && $messageDetails["reading_status"] == 1) {
            $this->model->updateMessage($id, ["reading_status" => 2]);
        }
    }
    public function sendAffiliateMessage($senderInfo)
    {
        if (isset($_POST["message_subject"]) && isset($_POST["message_body"]) && isset($_POST["csrf_token"]) && isset($_POST["receiver_username"]) && $senderInfo["membership"] != 1) {
            $flag = false;
            if ($senderInfo["membership_end_time"] == "Lifetime") {
                $flag = true;
            } else {
                if (is_numeric($senderInfo["membership_end_time"]) && time() <= $senderInfo["membership_end_time"]) {
                    $flag = true;
                }
            }
            if ($flag) {
                if (empty($_POST["message_subject"]) || empty($_POST["message_body"]) || empty($_POST["csrf_token"]) || empty($_POST["receiver_username"])) {
                    return ["success" => false, "message" => "All fields are required."];
                }
                if ($this->arrayCheck($_POST)) {
                    return ["success" => false, "message" => "Array not allowed here."];
                }
                if ($_POST["receiver_username"] == $senderInfo["username"]) {
                    return ["success" => false, "message" => "No no, you can't send message to yourself."];
                }
                $membersController = new MembersController();
                if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
                    return ["success" => false, "message" => "Invalid request."];
                }
                if (100 <= strlen($_POST["message_subject"])) {
                    return ["success" => false, "message" => "Message title is too long."];
                }
                if (500 <= strlen($_POST["message_body"])) {
                    return ["success" => false, "message" => "Message is too long."];
                }
                $receiverTotalInboxSize = $this->totalAffiliateInboxMessage($_POST["receiver_username"]);
                $senderTotalInboxSize = $this->totalAffiliateInboxMessage($senderInfo["username"]);
                $receiverTotalSentSize = $this->totalAffiliateSentMessage($_POST["receiver_username"]);
                $senderTotalSentSize = $this->totalAffiliateSentMessage($senderInfo["username"]);
                if ($senderInfo["referrer"] == $_POST["receiver_username"]) {
                    if ($this->maxMessageLimit <= $receiverTotalInboxSize + $receiverTotalSentSize) {
                        return ["success" => false, "message" => "The receiver user reached his/her maximum message limit. He/She can't accept new messages now."];
                    }
                    if ($this->maxMessageLimit <= $senderTotalInboxSize + $senderTotalSentSize) {
                        return ["success" => false, "message" => "You have reached your maximum message storage. Please delete some messages from your inbox/sent."];
                    }
                    $this->model->insertMessage(["sender_username" => $senderInfo["username"], "receiver_username" => $_POST["receiver_username"], "message_subject" => base64_encode($this->model->filter($_POST["message_subject"])), "message_body" => base64_encode($this->model->filter($_POST["message_body"])), "reading_status" => 1, "receiver_delete_status" => 1, "sender_delete_status" => 1, "sending_timestamp" => time()]);
                    return ["success" => true, "message" => "Message has been sent."];
                }
                $receiverDetails = $membersController->getUserDetails($_POST["receiver_username"]);
                if (empty($receiverDetails)) {
                    return ["success" => false, "message" => "Couldn't find any user with the username."];
                }
                if ($receiverDetails["referrer"] != $senderInfo["username"]) {
                    return ["success" => false, "message" => "Sorry ! The user is not in your downline. You can't message the user"];
                }
                if ($this->maxMessageLimit <= $receiverTotalInboxSize + $receiverTotalSentSize) {
                    return ["success" => false, "message" => "The receiver user reached his/her maximum message limit. He/She can't accept new messages now."];
                }
                if ($this->maxMessageLimit <= $senderTotalInboxSize + $senderTotalSentSize) {
                    return ["success" => false, "message" => "You have reached your maximum message storage. Please delete some messages from your inbox/sent."];
                }
                $this->model->insertMessage(["sender_username" => $senderInfo["username"], "receiver_username" => $_POST["receiver_username"], "message_subject" => base64_encode($this->model->filter($_POST["message_subject"])), "message_body" => base64_encode($this->model->filter($_POST["message_body"])), "reading_status" => 1, "receiver_delete_status" => 1, "sender_delete_status" => 1, "sending_timestamp" => time()]);
                return ["success" => true, "message" => "Message has been sent."];
            }
        }
    }
    public function getMessageDetails($id)
    {
        return $this->model->getMessageDetails($id);
    }
    public function deleteSentMessage($username)
    {
        if (isset($_GET["delete"]) && isset($_GET["token"]) && !empty($_GET["delete"]) && !empty($_GET["token"]) && is_numeric($_GET["delete"])) {
            $membersController = new MembersController();
            if ($membersController->getUserCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            $messageDetails = $this->getMessageDetails($_GET["delete"]);
            if (empty($messageDetails)) {
                return ["success" => false, "message" => "Couldn't find the message."];
            }
            if ($messageDetails["sender_username"] != $username) {
                return ["success" => false, "message" => "Couldn't find the message."];
            }
            if ($messageDetails["receiver_delete_status"] == 2) {
                $this->model->deleteMessage($_GET["delete"]);
                return ["success" => false, "message" => "Message has been deleted."];
            }
            $this->model->updateMessage($_GET["delete"], ["sender_delete_status" => 2]);
            return ["success" => false, "message" => "Message has been deleted."];
        }
    }
    public function deleteInboxMessage($username)
    {
        if (isset($_GET["delete"]) && isset($_GET["token"]) && !empty($_GET["delete"]) && !empty($_GET["token"]) && is_numeric($_GET["delete"])) {
            $membersController = new MembersController();
            if ($membersController->getUserCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            $messageDetails = $this->getMessageDetails($_GET["delete"]);
            if (empty($messageDetails)) {
                return ["success" => false, "message" => "Couldn't find the message."];
            }
            if ($messageDetails["receiver_username"] != $username) {
                return ["success" => false, "message" => "Couldn't find the message."];
            }
            if ($messageDetails["sender_delete_status"] == 2) {
                $this->model->deleteMessage($_GET["delete"]);
                return ["success" => false, "message" => "Message has been deleted."];
            }
            $this->model->updateMessage($_GET["delete"], ["receiver_delete_status" => 2]);
            return ["success" => false, "message" => "Message has been deleted."];
        }
    }
    public function totalAffiliateUnreadMessage($username)
    {
        return $this->model->totalAffiliateUnreadMessage($username);
    }
}

?>