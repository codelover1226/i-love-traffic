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
class PromotionalEmailsModel extends Model
{
    private $table = "ntk_promotional_emails";
    public function addEmail($data)
    {
        $this->insertData($this->table, $data);
    }
    public function getEmails()
    {
        return $this->getAll($this->table, 100, 0, "DESC");
    }
    public function updateEmail($id, $data)
    {
        $this->updateData($this->table, "id", $id, $data);
    }
    public function getEmailDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function deleteEmail($id)
    {
        $this->deleteData($this->table, $id);
    }
}

?>