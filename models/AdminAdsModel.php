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
class AdminAdsModel extends Model
{
    private $table = "ntk_admin_ads";
    public function addAd($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function totalAds()
    {
        return $this->countAll($this->table);
    }
    public function adsList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC");
    }
    public function updateAd($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function getAd()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE status = 1 ORDER BY RAND() LIMIT 1";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        $bannerData = $handler->fetch(PDO::FETCH_ASSOC);
        return $bannerData;
    }
    public function getAdDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function increaseAdClicks($id)
    {
        $query = "UPDATE " . $this->table . " SET total_clicks = total_clicks + 1 WHERE id = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($id));
        $handler->execute();
    }
    public function deleteAd($id)
    {
        return $this->deleteData($this->table, $id);
    }
}

?>