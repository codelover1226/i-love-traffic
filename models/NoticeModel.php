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
class NoticeModel extends Model
{
    private $table = "ntk_notice";
    public function getNotice()
    {
        return $this->getSingle($this->table, "id", 1);
    }
    public function updateNotice($data)
    {
        return $this->updateData($this->table, "id", 1, $data);
    }
}

?>