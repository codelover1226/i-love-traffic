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
class TextAdsModel extends Model
{
    private $table = "ntk_text_ads";
    public function addTextAd($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function totalTextAds()
    {
        return $this->countAll($this->table);
    }
    public function totalUserTextAds($username)
    {
        return $this->countWithCondition($this->table, "username", $username);
    }
    public function textAdsList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC");
    }
    public function userTextAdsList($limit, $offset, $username)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC", "username", $username);
    }
    public function updateTextAd($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function getTextAd()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE credits != 0 AND status = 1 ORDER BY RAND() LIMIT 1";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        $textAdData = $handler->fetch(PDO::FETCH_ASSOC);
        if (!empty($textAdData)) {
            $query = "UPDATE " . $this->table . " SET credits = credits - 1, total_views = total_views + 1 WHERE id = " . $textAdData["id"];
            $handler = $this->getDBConnection()->prepare($query);
            $handler->execute();
            return $textAdData;
        }
    }
    public function getTextAdDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function increaseTextAdClicks($id)
    {
        $query = "UPDATE " . $this->table . " SET total_clicks = total_clicks + 1 WHERE id = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($id));
        $handler->execute();
    }
    public function increaseTextAdCredits($id, $amount)
    {
        $query = "UPDATE " . $this->table . " SET credits = credits + " . $amount . " WHERE id = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($id));
        $handler->execute();
    }
    public function deductTextAdCredits($amount, $id)
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
    public function totalTextAdViews()
    {
        $query = "SELECT SUM(total_views) AS total_views FROM " . $this->table;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
}

?>