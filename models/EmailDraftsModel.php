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
class EmailDraftsModel extends Model
{
    private $table = "ntk_email_drafts";
    public function addDraft($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function getDraftInfo($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function updateDraft($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function deleteDraft($id)
    {
        return $this->deleteData($this->table, $id, "id");
    }
    public function totalDrafts($username)
    {
        return $this->countWithCondition($this->table, "username", $username);
    }
    public function getAllDrafts($username, $offset, $limit)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC", "username", $username);
    }
}

?>