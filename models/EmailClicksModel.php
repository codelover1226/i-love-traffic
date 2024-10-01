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
class EmailClicksModel extends Model
{
    private $table = "ntk_email_clicks";
    public function addNewClick($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function totalEmailclicks()
    {
        return $this->countAll($this->table);
    }
    public function emailClickList($limit, $offset)
    {
        $emailsController = new EmailsController();
        $emailTable = $emailsController->getTable();
        $query = "SELECT " . $emailTable . ".email_subject, " . $this->table . ".* \n        FROM " . $emailTable . ", " . $this->table . " WHERE " . $this->table . ".email_id = " . $emailTable . ".id ORDER BY id DESC LIMIT " . $limit . " OFFSET " . $offset;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function activityContestLeaderboard($startDate, $endDate)
    {
        $startDate = strtotime($startDate . "00:00:00");
        $endDate = strtotime($endDate . "23:59:59");
        $query = "SELECT COUNT(*) as total_clicks, username FROM " . $this->table . " WHERE \n        click_timestamp BETWEEN " . $startDate . " AND " . $endDate . " GROUP BY username ORDER BY total_clicks DESC LIMIT 20";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTable()
    {
        return $this->table;
    }
    public function topClickersThisMonth()
    {
        $startDate = "01-" . date("m-Y") . " 00:00:00";
        $endDate = date("j-m-Y") . " 23:59:59";
        $startTimeStamp = strtotime($startDate);
        $endTimeStamp = strtotime($endDate);
        $query = "SELECT COUNT(id) as total_clicks, username FROM " . $this->table . " WHERE click_timestamp BETWEEN " . $startTimeStamp . " AND " . $endTimeStamp . " \n        GROUP BY username ORDER BY total_clicks DESC LIMIT 10";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function checkUserEmailClick($username, $emailId)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE username = ? AND email_id = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->bindValue(2, $this->filter($emailId));
        $handler->execute();
        return $handler->fetchColumn();
    }
    public function deleteOldHistory()
    {
        $currentTime = time();
        $validityTime = 7776000;
        $oldTime = $currentTime - $validityTime;
        $query = "DELETE FROM " . $this->table . " WHERE click_timestamp <= " . $oldTime;
        $handler = $this->getDBConnection()->prepare($query);
        return $handler->execute();
    }
    public function totalClicksToday($username)
    {
        $startTimestamp = strtotime("today midnight");
        $endTimestamp = strtotime("tomorrow midnight") - 1;
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE username = ? AND click_timestamp BETWEEN " . $startTimestamp . " AND " . $endTimestamp;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
        return $handler->fetchColumn();
    }
}

?>