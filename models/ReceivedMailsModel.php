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
class ReceivedMailsModel extends Model
{
    private $emailTable = "ntk_emails";
    private $clickTable = "ntk_email_clicks";
    public function totalReceivedMails($username)
    {
        $websiteSettingsController = new SiteSettingsController();
        $settingsData = $websiteSettingsController->getSettings();
        $emailValiditySeconds = $settingsData["email_validity"] * 24 * 60 * 60;
        $startTime = time() - $emailValiditySeconds;
        $query = "SELECT COUNT(*) FROM " . $this->emailTable . " \n        WHERE " . $this->emailTable . ".sender_username != '" . $username . "' AND ". $this->emailTable . ".sending_time >= " . $startTime . " \n        AND " . $this->emailTable . ".total_clicks < " . $this->emailTable . ".credits_assign \n        AND " . $this->emailTable . ".id NOT IN (SELECT " . $this->clickTable . ".email_id FROM " . $this->clickTable . "\n        WHERE " . $this->clickTable . ".username = '" . $username . "' AND " . $this->clickTable . ".click_timestamp > " . $startTime . ")";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchColumn();
    }
    public function receivedMailsList($username)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalReceivedMails($username);
            $total_offset = ceil($total / 30);
            if ($_GET["page"] - 1 < 0) {
                $offset = 0;
            } else {
                if ($total_offset < $_GET["page"] - 1) {
                    $offset = 0;
                } else {
                    $offset = ($_GET["page"] - 1) * 30;
                }
            }
        }
        $websiteSettingsController = new SiteSettingsController();
        $settingsData = $websiteSettingsController->getSettings();
        $emailValiditySeconds = $settingsData["email_validity"] * 24 * 60 * 60;
        $startTime = time() - $emailValiditySeconds;
        $query = "SELECT " . $this->emailTable . ".* FROM " . $this->emailTable . " \n        WHERE " . $this->emailTable . ".sender_username != '" . $username . "' AND " . $this->emailTable . ".sending_time >= " . $startTime . " \n        AND " . $this->emailTable . ".total_clicks < " . $this->emailTable . ".credits_assign \n        AND " . $this->emailTable . ".id NOT IN (SELECT " . $this->clickTable . ".email_id FROM " . $this->clickTable . "\n        WHERE " . $this->clickTable . ".username = '" . $username . "' AND " . $this->clickTable . ".click_timestamp > " . $startTime . ") \n        ORDER BY " . $this->emailTable . ".id DESC LIMIT 30 OFFSET " . $offset;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>