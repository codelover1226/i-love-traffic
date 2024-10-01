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
class BannedEmailsModel extends Model
{
    private $table = "ntk_banned_emails";
    public function addNewEmail($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function emailList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "ASC");
    }
    public function getEmailDetails($email)
    {
        return $this->getSingle($this->table, "email", $email);
    }
    public function totalEmails()
    {
        return $this->countAll($this->table);
    }
    public function deleteEmail($id)
    {
        return $this->deleteData($this->table, $id);
    }
}

?>