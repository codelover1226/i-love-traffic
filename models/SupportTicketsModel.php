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
class SupportTicketsModel extends Model
{
    private $table = "ntk_support_tickets";
    private $reply_table = "ntk_support_tickets_replies";
    public function insertTicket($data)
    {
        $this->insertData($this->table, $data);
    }
    public function insertReply($data)
    {
        $this->insertData($this->reply_table, $data);
    }
    public function totalTickets()
    {
        return $this->countAll($this->table);
    }
    public function totalTicketsByStatus($status)
    {
        return $this->countWithCondition($this->table, "ticket_status", $status);
    }
    public function totalUserTickets($username)
    {
        return $this->countWithCondition($this->table, "ticket_author_username", $username);
    }
    public function totalUserTicketByStatus($username, $status)
    {
        $sql = "SELECT COUNT(*) FROM " . $this->table . " WHERE ticket_author_username = ? AND ticket_status = ? ORDER BY id DESC";
        $handler = $this->getDBConnection()->prepare($sql);
        $handler->bindValue(1, $this->filter($username));
        $handler->bindValue(2, $this->filter($status));
        $handler->execute();
        return $handler->fetchColumn();
    }
    public function updateTicket($id, $data)
    {
        $this->updateData($this->table, "id", $id, $data);
    }
    public function updateReply($id, $data)
    {
        $this->updateData($this->reply_table, "id", $id, $data);
    }
    public function deleteTicket($id)
    {
        $this->deleteData($this->table, $id);
    }
    public function getAllTickets($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC");
    }
    public function getAllTicketsByStatus($limit, $offset, $status)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC", "ticket_status", $status);
    }
    public function getUserTicketsList($limit, $offset, $username)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC", "ticket_author_username", $username);
    }
    public function getUserTicketsListByStatus($limit, $offset, $username, $status)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE ticket_author_username = ? AND ticket_status = ? ORDER BY id DESC LIMIT " . $limit . " OFFSET " . $offset;
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($username));
        $handler->bindValue(2, $this->filter($status));
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getTicketReplies($ticketID)
    {
        return $this->getAll($this->reply_table, 500, 0, "DESC", "ticket_id", $ticketID);
    }
    public function getTicketDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function getReplyDetails($id)
    {
        return $this->getSingle($this->reply_table, "id", $id);
    }
}

?>