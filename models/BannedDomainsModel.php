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
class BannedDomainsModel extends Model
{
    private $table = "ntk_banned_domains";
    public function addNewDomain($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function domainList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "ASC");
    }
    public function getDomainDetails($email)
    {
        return $this->getSingle($this->table, "email", $email);
    }
    public function totalDomains()
    {
        return $this->countAll($this->table);
    }
    public function deleteDomain($id)
    {
        return $this->deleteData($this->table, $id);
    }
}

?>