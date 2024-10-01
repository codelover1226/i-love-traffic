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
class MassMailModel extends Model
{
    private $table = "ntk_admin_mass_mail";
    public function addNewMail($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function updateMail($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
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
    public function getQueuedEmail()
    {
        return $this->getSingle($this->table, "status", 0);
    }
}

?>