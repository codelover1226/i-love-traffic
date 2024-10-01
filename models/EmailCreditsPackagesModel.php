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
class EmailCreditsPackagesModel extends Model
{
    private $table = "ntk_email_credits_packages";
    public function addNewPackage($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function updatePackage($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function getPackageDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function totalPackages()
    {
        return $this->countAll($this->table);
    }
    public function packagesList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "ASC");
    }
    public function deletePackage($id)
    {
        return $this->deleteData($this->table, $id);
    }
    public function allPackages()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE hidden = 2 ORDER BY id ASC";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>