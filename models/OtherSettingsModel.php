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
class OtherSettingsModel extends Model
{
    private $table = "ntk_other_settings";
    public function updateSettings($data, $settings)
    {
        return $this->updateData($this->table, "settings_name", $settings, $data);
    }
    public function getSettingsValue($settings)
    {
        return $this->getSingle($this->table, "settings_name", $settings);
    }
}

?>