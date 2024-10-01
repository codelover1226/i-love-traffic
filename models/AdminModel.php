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
class AdminModel extends Model
{
    private $table = "ntk_admins";
    private $token_table = "ntk_admin_tokens";
    public function getAdminDetails($username)
    {
        return parent::getSingle($this->table, "username", $username);
    }
    public function getTokenDetails($token)
    {
        return parent::getSingle($this->token_table, "token", $token);
    }
    public function countToken($token)
    {
        return parent::countWithCondition($this->token_table, "token", $token);
    }
    public function deleteToken($username)
    {
        return parent::deleteData($this->token_table, $username, "username");
    }
    public function createToken($username, $token)
    {
        return parent::insertData($this->token_table, ["username" => $username, "token" => $token]);
    }
    public function updateAdminDetails($username, $data)
    {
        return parent::updateData($this->table, "username", $username, $data);
    }
}

?>