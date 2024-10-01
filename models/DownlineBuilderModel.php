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
class DownlineBuilderModel extends Model
{
    private $table = "ntk_downline_builder_programs";
    private $adminTable = "ntk_admin_downline_builder_programs";
    private $otherSettingsTable = "ntk_other_settings";
    public function addUserDownlineProgram($data)
    {
        $this->insertData($this->table, $data);
    }
    public function addAdminDownlineProgram($data)
    {
        $this->insertData($this->adminTable, $data);
    }
    public function adminPrograms()
    {
        return $this->getAll($this->adminTable, 1000, 0, "DESC");
    }
    public function userProgramList($username)
    {
        return $this->getAll($this->table, 1000, 0, "ASC", "username", $username);
    }
    public function totalUserPrograms($username)
    {
        return $this->countWithCondition($this->table, "username", $username);
    }
    public function totalUserPrograms_Admin()
    {
        return $this->countAll($this->table);
    }
    public function userProgramsList_Admin($offset)
    {
        return $this->getAll($this->table, 30, $offset, "DESC");
    }
    public function deleteUserProgram($id)
    {
        $this->deleteData($this->table, $id);
    }
    public function deleteAdminProgram($id)
    {
        $this->deleteData($this->adminTable, $id);
    }
    public function insertSettings($data)
    {
        $this->insertData($this->otherSettingsTable, $data);
    }
    public function updateSettings($data)
    {
        $this->updateData($this->otherSettingsTable, "settings_name", "downline_builder_limits", $data);
    }
    public function getSettings()
    {
        return $this->getSingle($this->otherSettingsTable, "settings_name", "downline_builder_limits");
    }
    public function getUserProgramDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function getAdminProgramDetails($id)
    {
        return $this->getSingle($this->adminTable, "id", $id);
    }
}

?>