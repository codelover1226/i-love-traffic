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
class EmailReportsModel extends Model
{
    private $table = "ntk_email_reports";
    public function addNewReport($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function totalReports()
    {
        return $this->countAll($this->table);
    }
    public function updateReport($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function emailReportList($limit, $offset)
    {
        $emailsController = new EmailsController();
        $emailTable = $emailsController->getTable();
        $query = "SELECT " . $emailTable . ".email_subject, " . $emailTable . ".id AS email_id, " . $emailTable . ".sender_username, " . $this->table . ".* \n        FROM " . $emailTable . ", " . $this->table . " WHERE " . $this->table . ".email_id = " . $emailTable . ".id ORDER BY id DESC LIMIT " . $limit . " OFFSET " . $offset;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTable()
    {
        return $this->table;
    }
    public function checkUserEmailReport($reportSender, $emailId)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE report_sender = ? AND email_id = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($reportSender));
        $handler->bindValue(2, $this->filter($emailId));
        $handler->execute();
        return $handler->fetchColumn();
    }
}

?>