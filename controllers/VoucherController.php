<?php
$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    exit("");
}
if (file_exists("load_classes.php")) {
    require_once "load_classes.php";
} else {
    if (file_exists("../load_classes.php")) {
        require_once "../load_classes.php";
    } else {
        if (file_exists("../../load_classes.php")) {
            require_once "../../load_classes.php";
        }
    }
}
class VoucherController extends Model
{
    private $table = "ntk_nsms_vouchers";
    private $voucherUsedTable = "ntk_nsms_voucher_used_history";
    private $membersTable = "ntk_members";
    private $loginAdsCreditTable = "ntk_login_spotlight_ad_credit";
    public function totalVouchers()
    {
        return $this->countAll($this->table);
    }
    public function totalVoucherUsedHistory()
    {
        return $this->countAll($this->voucherUsedTable);
    }
    public function voucherDetails($voucherCode)
    {
        return $this->getSingle($this->table, "voucher_code", $voucherCode);
    }
    public function totalVoucherUsed($voucherCode)
    {
        return $this->countWithCondition($this->voucherUsedTable, "voucher_code", $voucherCode);
    }
    public function userVoucherUsedInfo($voucherCode, $username)
    {
        $query = "SELECT * FROM " . $this->voucherUsedTable . " WHERE voucher_code = ? AND username = ? LIMIT 1";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($voucherCode));
        $handler->bindValue(2, $this->filter($username));
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function createVoucher()
    {
        if (isset($_POST["voucher_code"]) && isset($_POST["email_credits"]) && isset($_POST["text_ad_credits"]) && isset($_POST["banner_ad_credits"]) && isset($_POST["login_ads"]) && isset($_POST["max_use"]) && isset($_POST["admin_csrf_token"])) {
            if (empty($_POST["voucher_code"])) {
                return ["success" => false, "message" => "Please enter a voucher code."];
            }
            if (empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "Invalid request."];
            }
            $adminController = new AdminController();
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if ($this->voucherDetails($_POST["voucher_code"])) {
                return ["success" => false, "message" => "Voucher code already exist."];
            }
            if (empty($_POST["email_credits"]) && empty($_POST["text_ad_credits"]) && empty($_POST["banner_ad_credits"]) && empty($_POST["login_ads"])) {
                return ["success" => false, "message" => "Please enter some rewards !"];
            }
            if (empty($_POST["max_use"])) {
                return ["success" => false, "message" => "Please enter max use."];
            }
            if (!is_numeric($_POST["max_use"]) || $_POST["max_use"] < 1) {
                return ["success" => false, "message" => "Invalid max use."];
            }
            if (!empty($_POST["email_credits"]) && !is_numeric($_POST["email_credits"]) || $_POST["email_credits"] < 0) {
                return ["success" => false, "message" => "Invalid email credits."];
            }
            if (!empty($_POST["text_ad_credits"]) && !is_numeric($_POST["text_ad_credits"]) || $_POST["text_ad_credits"] < 0) {
                return ["success" => false, "message" => "Invalid text ad credits."];
            }
            if (!empty($_POST["banner_ad_credits"]) && !is_numeric($_POST["banner_ad_credits"]) || $_POST["banner_ad_credits"] < 0) {
                return ["success" => false, "message" => "Invalid banner ad credits."];
            }
            if (!empty($_POST["login_ads"]) && !is_numeric($_POST["login_ads"]) || $_POST["login_ads"] < 0) {
                return ["success" => false, "message" => "Invalid login ad."];
            }
            $this->insertData($this->table, ["voucher_code" => $_POST["voucher_code"], "email_credits" => $_POST["email_credits"], "text_ad_credits" => $_POST["text_ad_credits"], "banner_ad_credits" => $_POST["banner_ad_credits"], "login_ads" => $_POST["login_ads"], "max_use" => $_POST["max_use"], "status" => 1, "creation_timestamp" => time()]);
            return ["success" => true, "message" => "Voucher has been created."];
        }
    }
    public function updateVoucher($voucherCode)
    {
        if (!$this->voucherDetails($voucherCode)) {
            return ["success" => false, "message" => "Voucher doesn't exist."];
        }
        if (isset($_POST["email_credits"]) && isset($_POST["text_ad_credits"]) && isset($_POST["banner_ad_credits"]) && isset($_POST["login_ads"]) && isset($_POST["max_use"]) && isset($_POST["admin_csrf_token"])) {
            if (empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "Invalid request."];
            }
            $adminController = new AdminController();
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (empty($_POST["email_credits"]) && empty($_POST["text_ad_credits"]) && empty($_POST["banner_ad_credits"]) && empty($_POST["login_ads"])) {
                return ["success" => false, "message" => "Please enter some rewards !"];
            }
            if (empty($_POST["max_use"])) {
                return ["success" => false, "message" => "Please enter max use."];
            }
            if (!is_numeric($_POST["max_use"]) || $_POST["max_use"] < 1) {
                return ["success" => false, "message" => "Invalid max use."];
            }
            if (!empty($_POST["email_credits"]) && !is_numeric($_POST["email_credits"]) || $_POST["email_credits"] < 0) {
                return ["success" => false, "message" => "Invalid email credits."];
            }
            if (!empty($_POST["text_ad_credits"]) && !is_numeric($_POST["text_ad_credits"]) || $_POST["text_ad_credits"] < 0) {
                return ["success" => false, "message" => "Invalid text ad credits."];
            }
            if (!empty($_POST["banner_ad_credits"]) && !is_numeric($_POST["banner_ad_credits"]) || $_POST["banner_ad_credits"] < 0) {
                return ["success" => false, "message" => "Invalid banner ad credits."];
            }
            if (!empty($_POST["login_ads"]) && !is_numeric($_POST["login_ads"]) || $_POST["login_ads"] < 0) {
                return ["success" => false, "message" => "Invalid login ad."];
            }
            $this->updateData($this->table, "voucher_code", $voucherCode, ["email_credits" => $_POST["email_credits"], "text_ad_credits" => $_POST["text_ad_credits"], "banner_ad_credits" => $_POST["banner_ad_credits"], "max_use" => $_POST["max_use"], "login_ads" => $_POST["login_ads"]]);
            return ["success" => true, "message" => "Voucher has been updated."];
        }
    }
    public function deleteVoucher()
    {
        if (isset($_GET["token"]) && isset($_GET["delete"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!$this->voucherDetails($_GET["delete"])) {
                return ["success" => false, "message" => "Voucher doen't exist."];
            }
            $this->deleteData($this->table, $_GET["delete"], "voucher_code");
            return ["success" => true, "message" => "Voucher has been deleted."];
        }
    }
    public function activateVoucher()
    {
        if (isset($_GET["token"]) && isset($_GET["activate"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!$this->voucherDetails($_GET["activate"])) {
                return ["success" => false, "message" => "Voucher doen't exist."];
            }
            $this->updateData($this->table, "voucher_code", $_GET["activate"], ["status" => 1]);
            return ["success" => true, "message" => "Voucher has been activated."];
        }
    }
    public function deactivateVoucher()
    {
        if (isset($_GET["token"]) && isset($_GET["deactivate"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!$this->voucherDetails($_GET["deactivate"])) {
                return ["success" => false, "message" => "Voucher doen't exist."];
            }
            $this->updateData($this->table, "voucher_code", $_GET["deactivate"], ["status" => 2]);
            return ["success" => true, "message" => "Voucher has been deactivated."];
        }
    }
    public function voucherPagination()
    {
        $limit = 30;
        $total = $this->totalVouchers();
        $total_offset = ceil($total / $limit);
        $current_page = 1;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] <= $total_offset && 0 < $_GET["page"]) {
            $current_page = $_GET["page"];
        }
        if (1 < $total_offset) {
            echo "<nav aria-label=\"Page navigation example\"><ul class=\"pagination\">";
            if (1 < $current_page) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"vouchers.php?page=" . ($current_page - 1) . "\">Previous</a></li>";
            }
            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"#\">" . $current_page . "</a></li>";
            if ($current_page < $total_offset) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"vouchers.php?page=" . ($current_page + 1) . "\">Next</a></li>";
            }
            echo "</ul></nav>";
        }
    }
    public function voucherList()
    {
        $offset = 0;
        $limit = 30;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalVouchers();
            $total_offset = ceil($total / $limit);
            if ($_GET["page"] - 1 < 0) {
                $offset = 0;
            } else {
                if ($total_offset < $_GET["page"] - 1) {
                    $offset = 0;
                } else {
                    $offset = ($_GET["page"] - 1) * $limit;
                }
            }
        }
        return $this->getAll($this->table, $limit, $offset, "DESC");
    }
    public function voucherUsedList()
    {
        $offset = 0;
        $limit = 30;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalVoucherUsedHistory();
            $total_offset = ceil($total / $limit);
            if ($_GET["page"] - 1 < 0) {
                $offset = 0;
            } else {
                if ($total_offset < $_GET["page"] - 1) {
                    $offset = 0;
                } else {
                    $offset = ($_GET["page"] - 1) * $limit;
                }
            }
        }
        return $this->getAll($this->voucherUsedTable, $limit, $offset, "DESC");
    }
    public function voucherUsedHistoryPagination()
    {
        $limit = 30;
        $total = $this->totalVoucherUsedHistory();
        $total_offset = ceil($total / $limit);
        $current_page = 1;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] <= $total_offset && 0 < $_GET["page"]) {
            $current_page = $_GET["page"];
        }
        if (1 < $total_offset) {
            echo "<nav aria-label=\"Page navigation example\"><ul class=\"pagination\">";
            if (1 < $current_page) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"vouchers.php?action=history&page=" . ($current_page - 1) . "\">Previous</a></li>";
            }
            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"#\">" . $current_page . "</a></li>";
            if ($current_page < $total_offset) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"vouchers.php?action=history&page=" . ($current_page + 1) . "\">Next</a></li>";
            }
            echo "</ul></nav>";
        }
    }
    public function applyVoucher($username)
    {
        if (isset($_POST["voucher_code"])) {
            if (empty($_POST["voucher_code"])) {
                return ["success" => false, "message" => "Please enter a voucher code."];
            }
            $voucherDetails = $this->voucherDetails($_POST["voucher_code"]);
            if (empty($voucherDetails)) {
                return ["success" => false, "message" => "Invalid voucher code."];
            }
            $totalVoucherUsed = $this->totalVoucherUsed($_POST["voucher_code"]);
            if ($voucherDetails["max_use"] <= $totalVoucherUsed) {
                return ["success" => false, "message" => "Sorry ! The vouche rached it's max use limit."];
            }
            if ($voucherDetails["status"] != 1) {
                return ["success" => false, "message" => "Invalid voucher code."];
            }
            if ($this->userVoucherUsedInfo($_POST["voucher_code"], $username)) {
                return ["success" => false, "message" => "You already used this voucher."];
            }
            $query = "UPDATE " . $this->membersTable . " SET credits = credits + " . $voucherDetails["email_credits"] . ", \n                    banner_credits = banner_credits + " . $voucherDetails["banner_ad_credits"] . ", text_ad_credits = text_ad_credits + " . $voucherDetails["text_ad_credits"] . " \n                    WHERE username = ? LIMIT 1";
            $handler = $this->getDBConnection()->prepare($query);
            $handler->bindValue(1, $this->filter($username));
            $handler->execute();
            if (0 < $voucherDetails["login_ads"]) {
                for ($i = 1; $i <= $voucherDetails["login_ads"]; $i++) {
                    $this->insertData($this->loginAdsCreditTable, ["username" => $username, "created_at" => time(), "status" => 1]);
                }
            }
            $this->insertData($this->voucherUsedTable, ["username" => $username, "voucher_code" => $_POST["voucher_code"], "used_timestamp" => time()]);
            return ["success" => true, "message" => "Voucher reward has been added to your account."];
        }
    }
}

?>