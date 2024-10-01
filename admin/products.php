<?php
$title = "Products";
if(isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])){
    $title = "Edit Product";
    require_once "themes/default/pages/edit-product.admin.page.php";
}else{
    require_once "themes/default/pages/product-list.admin.page.php";
}
