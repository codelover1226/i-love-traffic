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
class CoopUrlsModel extends Model
{
    private $table = "ntk_coop_urls";
    public function addCoopUrl($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function totalCoopUrls()
    {
        return $this->countAll($this->table);
    }
    public function totalUserCoopUrls($username)
    {
        return $this->countWithCondition($this->table, "username", $username);
    }
    public function coopUrlsList($limit, $offset)
    {
        return $this->getAllByStatus($this->table, $limit, $offset, "ASC");
    }
    public function lastCoopUrlsList($limit)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE credits != 0 AND status = 2 ORDER BY updated_at ASC LIMIT ".$limit;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        $bannerData = $handler->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($bannerData)) {
            $query = "UPDATE " . $this->table . " SET credits = credits - 1, total_views = total_views + 1 WHERE credits != 0 AND status = 2 ORDER BY updated_at ASC LIMIT ".$limit;
            $handler = $this->getDBConnection()->prepare($query);
            $handler->execute();
            return $bannerData;
        }
    }
    public function userCoopUrlsList($limit, $offset, $username)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC", "username", $username);
    }
    public function updateCoopUrl($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function getCoopUrl()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE credits != 0 AND status = 2 ORDER BY RAND() LIMIT 1";
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
    public function getCoopUrlDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function increaseCoopUrlClicks($id)
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
    public function increaseCoopUrlCredits($id, $amount)
    {
        $query = "UPDATE " . $this->table . " SET credits = credits + " . $amount . " WHERE id = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($id));
        $handler->execute();
    }
    public function deductCoopUrlCredits($amount, $id)
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