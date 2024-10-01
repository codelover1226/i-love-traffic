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
class OrdersController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new OrdersModel();
    }
    public function orderList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalOrders();
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
        return $this->model->orderList(30, $offset);
    }
    public function totalOrders()
    {
        return $this->model->totalOrders();
    }
    public function orderPagination()
    {
        return $this->pagination(30, $this->totalOrders(), "orders.php");
    }
    public function markOrderAsPaid()
    {
        if (isset($_GET["paid"]) && isset($_GET["token"]) && !empty($_GET["paid"]) && !empty($_GET["token"]) && is_numeric($_GET["paid"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request"];
            }
            $this->model->updateOrder(["payment_status" => 1], $_GET["paid"]);
            return ["success" => true, "message" => "The order has been marked as paid."];
        }
    }
    public function markOrderAsUnpaid()
    {
        if (isset($_GET["unpaid"]) && isset($_GET["token"]) && !empty($_GET["unpaid"]) && !empty($_GET["token"]) && is_numeric($_GET["unpaid"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request"];
            }
            $this->model->updateOrder(["payment_status" => 0], $_GET["unpaid"]);
            return ["success" => true, "message" => "The order has been marked as unpaid."];
        }
    }
    public function recentOrders()
    {
        return $this->model->orderList(5, 0);
    }
    public function totalSales()
    {
        return $this->model->totalSales()["total_sales"];
    }
    public function totalAffiliateSales($username)
    {
        return $this->model->totalAffiliateSales($username);
    }
    public function affiliateSalesList($username)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalOrders();
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
        return $this->model->affiliateSalesList($username, 30, $offset);
    }
    public function affiliateSalesPagination($username)
    {
        return $this->pagination(30, $this->totalAffiliateSales($username), "affiliates.php");
    }
    public function topSellerThisMonth()
    {
        return $this->model->topSellerThisMonth();
    }
    public function addNewOrder($data)
    {
        return $this->model->addNewOrder($data);
    }
    public function recentUsersOrderHistory($username)
    {
        return $this->model->recentUsersOrderHistory($username);
    }
    public function salesContestLeaderboard($startDate, $endDate)
    {
        return $this->model->salesContestLeaderboard($startDate, $endDate);
    }
}

?>