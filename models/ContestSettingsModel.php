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
class ContestSettingsModel extends Model
{
    private $table = "ntk_contests";
    public function getSettings($contest)
    {
        return $this->getSingle($this->table, "contest_title", $contest);
    }
    public function updateContestSettings($data, $contest)
    {
        return $this->updateData($this->table, "contest_title", $contest, $data);
    }
}

?>