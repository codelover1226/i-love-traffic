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
class TmpTransactionsModel extends Model
{
    private $table = "ntk_tmp_transactions";
    public function add($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function getInfo($trx_id, $payment_method)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE trx_id = ? AND payment_method = ? LIMIT 1";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($trx_id));
        $handler->bindValue(2, $this->filter($payment_method));
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function delete($trx_id, $payment_method)
    {
        $query = "DELETE FROM " . $this->table . " WHERE trx_id = ? AND payment_method = ?";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->filter($trx_id));
        $handler->bindValue(2, $this->filter($payment_method));
        return $handler->execute();
    }
    public function deleteOldHistory()
    {
        $currentTime = time();
        $validityTime = 7776000;
        $oldTime = $currentTime - $validityTime;
        $query = "DELETE FROM " . $this->table . " WHERE timestamp <= " . $oldTime;
        $handler = $this->getDBConnection()->prepare($query);
        return $handler->execute();
    }
}

?>