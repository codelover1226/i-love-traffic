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
class ChatGPTModel extends Model
{
    private $settings_table = "ntk_chatGPTSettings";
    private $prompt_table = "ntk_chatGPT_prompt_history";
    public function getSettings()
    {
        return $this->getSingle($this->settings_table, "id", 1);
    }
    public function updateSettings($data)
    {
        $this->updateData($this->settings_table, "id", 1, $data);
    }
    public function insertPrompt($data)
    {
        $this->insertData($this->prompt_table, $data);
    }
    public function totalUserPrompt($username)
    {
        return $this->countWithCondition($this->prompt_table, "username", $username);
    }
    public function userPromotList($limit, $offset, $username)
    {
        return $this->getAll($this->prompt_table, $limit, $offset, "DESC", "username", $username);
    }
    public function totalUserPromptCurrentMonth($username)
    {
        $startOfMonth = strtotime(date("Y-m-01 00:00:00"));
        $endOfMonth = strtotime(date("Y-m-01 00:00:00", strtotime("+1 month")));
        $query = "SELECT COUNT(*) FROM " . $this->prompt_table . " \n          WHERE username = ? \n          AND prompt_timestamp >= " . $startOfMonth . " \n          AND prompt_timestamp < " . $endOfMonth;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
        return $handler->fetchColumn();
    }
    public function getPromptDetails($id)
    {
        return $this->getSingle($this->prompt_table, "id", $id);
    }
}

?>