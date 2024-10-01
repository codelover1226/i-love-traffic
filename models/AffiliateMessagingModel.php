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
class AffiliateMessagingModel extends Model
{
    private $table = "ntk_affiliate_messages";
    public function insertMessage($data)
    {
        $this->insertData($this->table, $data);
    }
    public function deleteMessage($id)
    {
        $this->deleteData($this->table, $id, "id");
    }
    public function updateMessage($id, $data)
    {
        $this->updateData($this->table, "id", $id, $data);
    }
    public function getMessageDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function totalAffiliateInboxMessage($username)
    {
        $sql = "SELECT COUNT(*) FROM " . $this->table . " WHERE receiver_username = ? AND receiver_delete_status != 2 ORDER BY id DESC";
        $handler = $this->getDBConnection()->prepare($sql);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
        return $handler->fetchColumn();
    }
    public function totalAffiliateSentMessage($username)
    {
        $sql = "SELECT COUNT(*) FROM " . $this->table . " WHERE sender_username = ? AND sender_delete_status != 2 ORDER BY id DESC";
        $handler = $this->getDBConnection()->prepare($sql);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
        return $handler->fetchColumn();
    }
    public function affilaiteInboxList($limit, $offset, $username)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE receiver_username = ? AND receiver_delete_status != 2 ORDER BY reading_status ASC, id DESC LIMIT " . $limit . " OFFSET " . $offset;
        $handler = $this->getDBConnection()->prepare($sql);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function affiliateSentList($limit, $offset, $username)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE sender_username = ? AND sender_delete_status != 2 ORDER BY id DESC LIMIT " . $limit . " OFFSET " . $offset;
        $handler = $this->getDBConnection()->prepare($sql);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function totalAffiliateUnreadMessage($username)
    {
        $sql = "SELECT COUNT(*) FROM " . $this->table . " WHERE receiver_username = ? AND receiver_delete_status != 2 AND reading_status != 2";
        $handler = $this->getDBConnection()->prepare($sql);
        $handler->bindValue(1, $this->filter($username));
        $handler->execute();
        return $handler->fetchColumn();
    }
}

?>