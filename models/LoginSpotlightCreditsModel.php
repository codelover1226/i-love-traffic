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
class LoginSpotlightCreditsModel extends Model
{
    private $table = "ntk_login_spotlight_ad_credit";
    public function addCredit($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function countUserActiveCredit($username)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE username = ? AND status = 1";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
        return $handler->fetchColumn();
    }
    public function deleteUserCredit($username)
    {
        $query = "DELETE FROM " . $this->table . " WHERE username = ? LIMIT 1";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        return $handler->execute();
    }
}

?>