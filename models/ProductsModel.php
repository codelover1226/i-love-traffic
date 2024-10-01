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
class ProductsModel extends Model
{
    private $table = "ntk_products";
    public function addNewProduct($data)
    {
        return $this->insertData($this->table, $data);
    }
    public function updateProduct($data, $id)
    {
        return $this->updateData($this->table, "id", $id, $data);
    }
    public function getProductDetails($id)
    {
        return $this->getSingle($this->table, "id", $id);
    }
    public function totalProducts()
    {
        return $this->countAll($this->table);
    }
    public function totalActiveProducts()
    {
        return $this->countWithCondition($this->table, "status", 1);
    }
    public function productList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC");
    }
    public function activeProductList($limit, $offset)
    {
        return $this->getAll($this->table, $limit, $offset, "DESC", "status", 1);
    }
    public function deleteProduct($id)
    {
        return $this->deleteData($this->table, $id);
    }
    public function allProducts()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE status = 1 AND hidden = 2";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>