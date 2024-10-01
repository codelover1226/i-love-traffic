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
class OrdersModel extends Model
{
    private $table = "ntk_orders";
    public function addNewOrder($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function orderList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC");
    }
    public function getOrderDetails($email)
    {
        return $this->getSingle($this->table, "email", $email);
    }
    public function totalOrders()
    {
        return $this->countAll($this->table);
    }
    public function deleteOrder($id)
    {
        return $this->deleteData($this->table, $id);
    }
    public function updateOrder($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function totalSales()
    {
        $query = "SELECT SUM(product_price) AS total_sales FROM " . $this->table . " WHERE payment_status = 1";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetch(PDO::FETCH_ASSOC);
    }
    public function totalAffiliateSales($username)
    {
        return $this->countWithCondition($this->table, "affiliate_username", $username);
    }
    public function affiliateSalesList($username, $limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC", "affiliate_username", $username);
    }
    public function topSellerThisMonth()
    {
        $startDate = "01-" . date("m-Y") . " 00:00:00";
        $endDate = date("j-m-Y") . " 23:59:59";
        $startTimeStamp = strtotime($startDate);
        $endTimeStamp = strtotime($endDate);
        $query = "SELECT *, COUNT(id) AS total_sales FROM " . $this->table . " WHERE affiliate_username != '' AND order_timestamp BETWEEN " . $startTimeStamp . " AND " . $endTimeStamp . " \n        GROUP BY affiliate_username ORDER BY COUNT(id) DESC LIMIT 10";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function recentUsersOrderHistory($username)
    {
        return $this->getAll($this->table, 30, 0, "DESC", "buyer_username", $username);
    }
    public function salesContestLeaderboard($startDate, $endDate)
    {
        $startDate = strtotime($startDate . "00:00:00");
        $endDate = strtotime($endDate . "23:59:59");
        $query = "SELECT SUM(product_price) as total_sold, affiliate_username FROM " . $this->table . " WHERE \n        order_timestamp BETWEEN " . $startDate . " AND " . $endDate . " GROUP BY affiliate_username ORDER BY total_sold DESC LIMIT 20";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>