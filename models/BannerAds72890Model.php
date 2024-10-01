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
class BannerAds72890Model extends Model
{
    private $table = "ntk_banner_ads_728_90";
    public function addBannerAd($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function totalBannerAds()
    {
        return $this->countAll($this->table);
    }
    public function totalUserBannerAds($username)
    {
        return $this->countWithCondition($this->table, "username", $username);
    }
    public function bannerAdsList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC");
    }
    public function userBannerAdsList($limit, $offset, $username)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC", "username", $username);
    }
    public function updateBannerAd($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function getBannerAd()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE credits != 0 AND status = 1 ORDER BY RAND() LIMIT 1";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        $bannerData = $handler->fetch(PDO::FETCH_ASSOC);
        if (!empty($bannerData)) {
            $query = "UPDATE " . $this->table . " SET credits = credits - 1, total_views = total_views + 1 WHERE id = " . $bannerData["id"];
            $handler = $this->getDBConnection()->prepare($query);
            $handler->execute();
            return $bannerData;
        }
    }
    public function getBannerAdDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function increaseBannerAdClicks($id)
    {
        $query = "UPDATE " . $this->table . " SET total_clicks = total_clicks + 1 WHERE id = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($id));
        $handler->execute();
    }
    public function totalBannerViews()
    {
        $query = "SELECT SUM(total_views) AS total_views FROM " . $this->table;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function increaseBannerAdCredits($id, $amount)
    {
        $query = "UPDATE " . $this->table . " SET credits = credits + " . $amount . " WHERE id = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($id));
        $handler->execute();
    }
    public function deductBannerAdCredits($amount, $id)
    {
        $query = "UPDATE " . $this->table . " SET credits = credits - " . $amount . " WHERE id = ?";
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