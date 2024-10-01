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
class ProductsController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new ProductsModel();
    }
    public function addProduct()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_title"]) && isset($_POST["product_description"]) && isset($_POST["product_price"]) && isset($_POST["status"]) && isset($_POST["admin_csrf_token"]) && isset($_POST["hidden"]) && isset($_POST["special_offer"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["product_title"]) || empty($_POST["product_description"]) || empty($_POST["product_price"]) || empty($_POST["status"]) || empty($_POST["admin_csrf_token"]) || empty($_POST["hidden"]) || empty($_POST["special_offer"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["product_price"]) || $_POST["product_price"] < 0) {
                return ["success" => false, "message" => "Invalid product price."];
            }
            if ($_POST["special_offer"] != 1 && $_POST["special_offer"] != 2) {
                return ["success" => false, "message" => "Invalid value for special offer."];
            }
            if ($_POST["status"] != 1 && $_POST["status"] != 2) {
                return ["success" => false, "message" => "Invalid product status."];
            }
            if (1000 < strlen($_POST["product_description"])) {
                return ["success" => false, "message" => "Product description is too long. You can add max 1000 characters in description."];
            }
            if (255 < strlen($_POST["product_title"])) {
                return ["success" => false, "message" => "Product description is too long. You can add max 255 characters in description."];
            }
            if (!is_numeric($_POST["hidden"]) || $_POST["hidden"] < 0 || 2 < $_POST["hidden"]) {
                return ["success" => false, "message" => "Invalid hidden status."];
            }
            $this->model->addNewProduct(["product_title" => $_POST["product_title"], "product_description" => $_POST["product_description"], "product_price" => $_POST["product_price"], "status" => $_POST["status"], "special_offer" => $_POST["special_offer"], "hidden" => $_POST["hidden"]]);
            return ["success" => true, "message" => "Product has been added."];
        }
    }
    public function updateProduct($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_title"]) && isset($_POST["product_description"]) && isset($_POST["product_price"]) && isset($_POST["status"]) && isset($_POST["admin_csrf_token"]) && isset($_POST["hidden"]) && isset($_POST["special_offer"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "You have sent an array. We don't allow array here."];
            }
            $adminController = new AdminController();
            if (empty($_POST["product_title"]) || empty($_POST["product_description"]) || empty($_POST["product_price"]) || empty($_POST["status"]) || empty($_POST["admin_csrf_token"]) || empty($_POST["hidden"]) || empty($_POST["special_offer"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["product_price"]) || $_POST["product_price"] < 0) {
                return ["success" => false, "message" => "Invalid product price."];
            }
            if ($_POST["special_offer"] != 1 && $_POST["special_offer"] != 2) {
                return ["success" => false, "message" => "Invalid value for special offer."];
            }
            if ($_POST["status"] != 1 && $_POST["status"] != 2) {
                return ["success" => false, "message" => "Invalid product status."];
            }
            if (1000 < strlen($_POST["product_description"])) {
                return ["success" => false, "message" => "Product description is too long. You can add max 1000 characters in description."];
            }
            if (255 < strlen($_POST["product_title"])) {
                return ["success" => false, "message" => "Product description is too long. You can add max 255 characters in description."];
            }
            if (!is_numeric($_POST["hidden"]) || $_POST["hidden"] < 0 || 2 < $_POST["hidden"]) {
                return ["success" => false, "message" => "Invalid hidden status."];
            }
            $this->model->updateProduct(["product_title" => $_POST["product_title"], "product_description" => $_POST["product_description"], "product_price" => $_POST["product_price"], "status" => $_POST["status"], "special_offer" => $_POST["special_offer"], "hidden" => $_POST["hidden"]], $id);
            return ["success" => true, "message" => "Product has been updated."];
        }
    }
    public function deleteProduct()
    {
        if (isset($_GET["delete"]) && isset($_GET["token"])) {
            $adminController = new AdminController();
            if (!empty($_GET["delete"]) && !empty($_GET["token"]) && is_numeric($_GET["delete"]) && $adminController->getAdminCSRFToken() == $_GET["token"]) {
                $productDetails = $this->getProductDetails($_GET["delete"]);
                if (empty($productDetails)) {
                    return ["success" => false, "message" => "Couldn't find the product."];
                }
                $this->model->deleteProduct($_GET["delete"]);
                return ["success" => true, "message" => "The product has been deleted."];
            }
        }
    }
    public function getProductDetails($id)
    {
        return $this->model->getProductDetails($id);
    }
    public function productList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalProducts();
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
        return $this->model->productList(30, $offset);
    }
    public function totalProducts()
    {
        return $this->model->totalProducts();
    }
    public function productsPagination()
    {
        $this->pagination(30, $this->totalProducts(), "products.php");
    }
    public function getActiveProducts()
    {
        return $this->model->allProducts();
    }
}

?>