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
class SpecialOfferPagesModel extends Model
{
    private $table = "ntk_special_offer_pages";
    public function addnewPage($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function updatePage($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function getPageDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function totalPages()
    {
        return $this->countAll($this->table);
    }
    public function pageList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "ASC");
    }
    public function deletePage($id)
    {
        return $this->deleteData($this->table, $id);
    }
    public function removeOtherPageFromLogin($id)
    {
        $query = "UPDATE " . $this->table . " SET show_on_login = 2 WHERE id != ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($id));
        $handler->execute();
    }
    public function getLoginOfferPage()
    {
        return $this->getSingle($this->table, "show_on_login", 1);
    }
}

?>