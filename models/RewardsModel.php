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
class RewardsModel extends Model
{
    private $table = "ntk_rewards";
    public function addRewards($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function totalAffiliateRewards($username)
    {
        return $this->countWithCondition($this->table, "username", $username);
    }
    public function affilaiteRewardsList($username, $limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC", "username", $username);
    }
}

?>