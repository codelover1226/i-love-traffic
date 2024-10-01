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
class RandomRewardsModel extends Model
{
    private $table = "ntk_random_rewards_history";
    private $settingsTable = "ntk_random_rewards_settings";
    public function addRewardHistory($data)
    {
        $this->insertData($this->table, $data);
    }
    public function checkClickRewards($username, $todayClick)
    {
        $startTimestamp = strtotime("today midnight");
        $endTimestamp = strtotime("tomorrow midnight") - 1;
        $query = "SELECT * FROM " . $this->table . " WERHE username = ? and total_clicks = ? AND reward_timestamp BETWEEN " . $startTimestamp . " AND " . $endTimestamp;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->bindValue(2, $this->filter($todayClick));
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateSettings($data)
    {
        $this->updateData($this->settingsTable, "id", 1, $data);
    }
    public function getSettings()
    {
        return $this->getSingle($this->settingsTable, "id", 1);
    }
}

?>