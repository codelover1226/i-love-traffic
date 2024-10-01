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
class WithdrawalRequestsModel extends Model
{
    private $table = "ntk_withdrawal_request";
    public function addNewRequest($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function getRequestDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function updateWithdrawalRequest($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function totalPendingWithdrawalRequests()
    {
        return $this->countWithCondition($this->table, "status", 1);
    }
    public function totalPaidWithdrawalRequests()
    {
        return $this->countWithCondition($this->table, "status", 2);
    }
    public function totalUserWithdrawalRequests($username)
    {
        return $this->countWithCondition($this->table, "username", $username);
    }
    public function pendingWithdrawalRequestsList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "ASC", "status", 1);
    }
    public function paidWithdrawalRequestsList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC", "status", 2);
    }
    public function userWithdrawalRequestsList($limit, $offset, $username)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC", "username", $username);
    }
    public function totalPaidAmount()
    {
        $query = "SELECT SUM(amount) AS total_paid_amount FROM " . $this->table . " WHERE status = 2";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function totalUserPaidAmount($username)
    {
        $query = "SELECT SUM(amount) AS total_paid_amount FROM " . $this->table . " WHERE status = 2 AND username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function totalUserPendingAmount($username)
    {
        $query = "SELECT SUM(amount) AS total_pending_amount FROM " . $this->table . " WHERE status = 1 AND username = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function totalAmount()
    {
        $query = "SELECT SUM(amount) AS total_amount FROM " . $this->table;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
}

?>