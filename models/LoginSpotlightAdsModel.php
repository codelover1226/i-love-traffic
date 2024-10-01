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
class LoginSpotlightAdsModel extends Model
{
    private $table = "ntk_login_spotlight_ads";
    public function addNewAd($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function totalAds()
    {
        return $this->countAll($this->table);
    }
    public function totalUserAds($username)
    {
        return $this->countWithCondition($this->table, "username", $username);
    }
    public function loginAdsList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC");
    }
    public function userLoginAdsList($limit, $offset, $username)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC", "username", $username);
    }
    public function updateLoginAd($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function getAdDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function getLoginAdDetailsByCreditKey($creditKey)
    {
        return $this->getSingle($this->table, "credit_key", $creditKey);
    }
    public function increaseLoginAdViews($id)
    {
        $query = "UPDATE " . $this->table . " SET total_views = total_views + 1 WHERE id = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($id));
        $handler->execute();
    }
    public function oneMonthAds()
    {
        $today = date("d-M-Y");
        $startDate = strtotime($today . " 00:00:00");
        $monthEnd = date("t-M-Y");
        $endDate = strtotime($monthEnd . " 00:00:00");
        $query = "SELECT * FROM " . $this->table . " WHERE ad_timestamp BETWEEN " . $startDate . " AND " . $endDate;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTodayAd()
    {
        $today = strtotime(strval(date("Y-m-d")) . " 00:00:00");
        $query = "SELECT * FROM " . $this->table . " WHERE status = 1 AND ad_timestamp = ? LIMIT 1";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($today));
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
}

?>