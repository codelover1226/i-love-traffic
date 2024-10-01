<?php 
if(isset($_GET["action"]) && $_GET["action"] == "add"){
    $title = "Add New Page";
    require_once "themes/default/pages/add-special-offer-page.admin.page.php";
}else if(isset($_GET["edit"]) && !empty($_GET["edit"]) && is_numeric($_GET["edit"])){
    $title = "Edit Page";
    require_once "themes/default/pages/edit-special-offer-page.admin.page.php";
}else{
    $title = "Special Offer Pages";
    require_once "themes/default/pages/special-offer-pages.admin.page.php";
}   