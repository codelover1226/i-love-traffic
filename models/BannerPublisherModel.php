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
class BannerPublisherModel extends Model
{
    private $table = "ntk_members";
    private $settingsTable = "ntk_other_settings";
    public function addBannerCredit($username)
    {
        $settings = $this->getSettings();
        $amount = $settings["settings_value"];
        $sql = "UPDATE " . $this->table . " SET banner_credits = banner_credits + " . $amount . " WHERE username = ?";
        $handler = $this->getDBConnection()->prepare($sql);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
    }
    public function updateBannerPublisherSettings($data)
    {
        $this->updateData($this->settingsTable, "settings_name", "banner_publisher", $data);
    }
    public function getSettings()
    {
        return $this->getSingle($this->settingsTable, "settings_name", "banner_publisher");
    }
}

?>