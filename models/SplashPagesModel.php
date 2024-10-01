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
class SplashPagesModel extends Model
{
    private $table = "ntk_splash_pages";
    public function addSplashPage($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function updateSplashPage($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function getSplashPageDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function totalSplashPages()
    {
        return $this->countAll($this->table);
    }
    public function splashPageList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "ASC");
    }
    public function allSplashPages()
    {
        return $this->getAll($this->table, 1000, 0, "ASC");
    }
    public function deleteSplashPage($id)
    {
        return $this->deleteData($this->table, $id);
    }
}

?>