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
class MembershipsModel extends Model
{
    private $table = "ntk_memberships";
    public function addNewMembership($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function updateMembership($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function getMembershipDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function totalMemberships()
    {
        return $this->countAll($this->table);
    }
    public function totalActiveMembership()
    {
        return $this->countWithCondition($this->table, "status", 1);
    }
    public function membershipsList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "ASC");
    }
    public function activeMembershpsList($limit, $offset)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE status = 1 AND subscription_type != 1 AND hidden = 2 ORDER BY price ASC";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteMembership($id)
    {
        return $this->deleteData($this->table, $id);
    }
    public function getTable()
    {
        return $this->table;
    }
}

?>