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
class LoginAdsModel extends Model
{
    private $table = "ntk_login_ads";
    public function addLoginAd($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function totalLoginAds()
    {
        return $this->countAll($this->table);
    }
    public function totalUserLoginAds($username)
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
    public function getLoginAd()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE credits != 0 AND status = 1 ORDER BY RAND() LIMIT 1";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        $loginData = $handler->fetch(PDO::FETCH_ASSOC);
        if (!empty($loginData)) {
            $query = "UPDATE " . $this->table . " SET credits = credits - 1, total_views = total_views + 1 WHERE id = " . $loginData["id"];
            $handler = $this->getDBConnection()->prepare($query);
            $handler->execute();
            return $loginData;
        }
    }
    public function getLoginAdDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function increaseLoginAdClicks($id)
    {
        $query = "UPDATE " . $this->table . " SET total_clicks = total_clicks + 1 WHERE id = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($id));
        $handler->execute();
    }
    public function totalLoginViews()
    {
        $query = "SELECT SUM(total_views) AS total_views FROM " . $this->table;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function increaseLoginAdCredits($id, $amount)
    {
        $query = "UPDATE " . $this->table . " SET credits = credits + " . $amount . " WHERE id = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($id));
        $handler->execute();
    }
    public function deductLoginAdCredits($amount, $id)
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