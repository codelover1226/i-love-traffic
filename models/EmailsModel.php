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
class EmailsModel extends Model
{
    private $table = "ntk_emails";
    public function addNewMail($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function updateMail($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function getMailDetailsByCreditKey($creditKey)
    {
        return $this->getSingle($this->table, "credit_key", $creditKey);
    }
    public function getMailDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function totalMails()
    {
        return $this->countAll($this->table);
    }
    public function mailList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC");
    }
    public function deleteMail($id)
    {
        return $this->deleteData($this->table, $id);
    }
    public function getTable()
    {
        return $this->table;
    }
    public function totalSentToday()
    {
        $todayStart = strtotime(strval(date("Y-m-d")) . " 00:00:00");
        $todayEnd = strtotime(strval(date("Y-m-d")) . " 23:59:59");
        $query = "SELECT SUM(total_sent) AS total_sent FROM " . $this->table . " WHERE sending_time BETWEEN " . $todayStart . " AND " . $todayEnd;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function totalEmailSent()
    {
        $query = "SELECT SUM(total_sent) AS total_sent FROM " . $this->table;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function totalEmailClicks()
    {
        $query = "SELECT SUM(total_clicks) AS total_email_clicks FROM " . $this->table;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function totalUserEmails($username)
    {
        return $this->countWithCondition($this->table, "sender_username", $username);
    }
    public function userEmailList($username, $limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC", "sender_username", $username);
    }
    public function totalUserEmailsToday($username)
    {
        $todayStart = strtotime(strval(date("Y-m-d")) . " 00:00:00");
        $todayEnd = strtotime(strval(date("Y-m-d")) . " 23:59:59");
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE sender_username = ? AND email_status = 2 AND sending_time BETWEEN " . $todayStart . " AND " . $todayEnd;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
        return $handler->fetchColumn();
    }
    public function addEmailClicks($id)
    {
        $query = "UPDATE " . $this->table . " SET total_clicks = total_clicks + 1 WHERE id = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($id));
        return $handler->execute();
    }
    public function getEmailsForSending()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email_status = 0 ORDER BY id ASC LIMIT 50";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getScheduleEmailForSending()
    {
        $startTime = time() - 60;
        $endTime = time() + 60;
        $query = "SELECT * FROM " . $this->table . " WHERE email_status = 3 AND sending_time BETWEEN " . $startTime . " AND " . $endTime;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateEmailStatus($id, $data)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
}

?>