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
class MembersTokenModel extends Model
{
    private $table = "ntk_member_tokens";
    public function addToken($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function deleteAllUserToken($username)
    {
        return $this->deleteData($this->table, $username, "username");
    }
    public function tokenDetails($token)
    {
        return $this->getSingle($this->table, "token", $token);
    }
}

?>