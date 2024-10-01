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
class LoginSpotlightAdClickModel extends Model
{
    private $table = "ntk_login_spotlight_clicks";
    public function addClickHistory($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function getTodayClickHistoryCount($username, $id)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE username = ? AND ad_id = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->bindValue(2, $this->filter($id));
        $handler->execute();
        return $handler->fetchColumn();
    }
}

?>