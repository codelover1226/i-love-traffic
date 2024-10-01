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
class WithdrawalRequestsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new WithdrawalRequestsModel();
    }
    public function totalPendingWithdrawalRequests()
    {
        return $this->model->totalPendingWithdrawalRequests();
    }
    public function totalPaidWithdrawalRequests()
    {
        return $this->model->totalPaidWithdrawalRequests();
    }
    public function pendingWithdrawalRequestsList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalPendingWithdrawalRequests();
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
        return $this->model->pendingWithdrawalRequestsList(30, $offset);
    }
    public function paidWithdrawalRequestsList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalPaidWithdrawalRequests();
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
        return $this->model->paidWithdrawalRequestsList(30, $offset);
    }
    public function withdrawalRequestsPagination()
    {
        $this->pagination(30, $this->totalPaidWithdrawalRequests(), "withdrawl-requests.php");
    }
    public function paidWithdrawalRequestsPagination()
    {
        $total = $this->totalPaidWithdrawalRequests();
        $total_offset = ceil($total / 30);
        $current_page = 1;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] <= $total_offset) {
            $current_page = $_GET["page"];
        }
        if (1 < $total_offset) {
            echo "<nav aria-label=\"Page navigation example\"><ul class=\"pagination\">";
            if (1 < $current_page) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"withdrawal-requests.php?action=paid&page=" . ($current_page - 1) . "\">Previous</a></li>";
            }
            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"#\">" . $current_page . "</a></li>";
            if ($current_page < $total_offset) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"withdrawal-requests.php?action=paid&page=" . ($current_page + 1) . "\">Next</a></li>";
            }
            echo "</ul></nav>";
        }
    }
    public function markAsPaid()
    {
        if (isset($_GET["paid"]) && isset($_GET["token"]) && !empty($_GET["paid"]) && !empty($_GET["token"])) {
            $adminController = new AdminController();
            if (is_numeric($_GET["paid"]) && 0 < $_GET["paid"] && $adminController->getAdminCSRFToken() == $_GET["token"]) {
                $this->model->updateWithdrawalRequest(["status" => 2, "paid_timestamp" => time()], $_GET["paid"]);
                return ["success" => true, "message" => "The request has been marked as paid."];
            }
        }
    }
    public function totalPaidAmount()
    {
        return $this->model->totalPaidAmount()["total_paid_amount"];
    }
    public function totalAmount()
    {
        return $this->model->totalAmount()["total_amount"];
    }
    public function totalUserWithdrawalRequests($username)
    {
        return $this->model->totalUserWithdrawalRequests($username);
    }
    public function userWithdrawalRequestsPagination($username)
    {
        return $this->pagination(30, $this->totalUserWithdrawalRequests($username), "withdrawal.php");
    }
    public function userWithdrawalRequestsList($username)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalPaidWithdrawalRequests();
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
        return $this->model->userWithdrawalRequestsList(30, $offset, $username);
    }
    public function totalUserPaidAmount($username)
    {
        return $this->model->totalUserPaidAmount($username)["total_paid_amount"];
    }
    public function totalUserPendingAmount($username)
    {
        return $this->model->totalUserPendingAmount($username)["total_pending_amount"];
    }
    public function addNewUserWithdrawalRequest($username, $userInfo)
    {
        $affiliateSettingsController = new AffiliateSettingsController();
        if (isset($_POST["amount"]) && isset($_POST["payment_gateway"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            $membersController = new MembersController();
            $affiliateSettings = $affiliateSettingsController->getSettings();
            if (empty($_POST["amount"]) || empty($_POST["payment_gateway"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["amount"])) {
                return ["success" => false, "message" => "Invalid amount."];
            }
            if ($userInfo["balance"] < $_POST["amount"]) {
                return ["success" => false, "message" => "You don't have enough fund to withdraw."];
            }
            if ($_POST["amount"] < $affiliateSettings["minimum_withdraw"]) {
                return ["success" => false, "message" => "Minimum withdraw amount is \$" . $affiliateSettings["minimum_withdraw"]];
            }
            $selectedPaymentGateway = $_POST["payment_gateway"];
            $paymentId = "";
            if ($selectedPaymentGateway == "PayPal" && $affiliateSettings["paypal"] == 1) {
                $selectedPaymentGateway = "PayPal";
                $paymentId = $userInfo["paypal"];
            } else {
                if ($selectedPaymentGateway == "Coinbase" && $affiliateSettings["btc_coinbase"] == 1) {
                    $selectedPaymentGateway = "Coinbase";
                    $paymentId = $userInfo["btc_coinbase"];
                } else {
                    if ($selectedPaymentGateway == "Skrill" && $affiliateSettings["skrill"] == 1) {
                        $selectedPaymentGateway = "Skrill";
                        $paymentId = $userInfo["skrill"];
                    } else {
                        if ($selectedPaymentGateway == "TransferWise" && $affiliateSettings["transfer_wise"] == 1) {
                            $selectedPaymentGateway = "TransferWise";
                            $paymentId = $userInfo["transfer_wise"];
                        } else {
                            if ($selectedPaymentGateway == "PerfectMoney" && $affiliateSettings["perfect_money"] == 1) {
                                $selectedPaymentGateway = "PerfectMoney";
                                $paymentId = $userInfo["perfect_money"];
                            } else {
                                if ($selectedPaymentGateway == "ETH Wallet" && $affiliateSettings["eth_wallet"] == 1) {
                                    $selectedPaymentGateway = "ETH Wallet";
                                    $paymentId = $userInfo["eth_wallet"];
                                } else {
                                    $selectedPaymentGateway = "";
                                }
                            }
                        }
                    }
                }
            }
            if (empty($selectedPaymentGateway) || empty($paymentId)) {
                return ["success" => false, "message" => "Invalid payment method."];
            }
            $this->model->addNewRequest(["username" => $username, "payment_gateway" => $selectedPaymentGateway, "payment_id" => $paymentId, "amount" => $_POST["amount"], "request_timestamp" => time(), "paid_timestamp" => "", "status" => 1]);
            $membersController->deductMemberBalance($username, $_POST["amount"], $userInfo);
            return ["success" => true, "message" => "Your payment requet has been sent. An admin will review this and will pay you soon."];
        }
    }
}

?>