<?php 
if(isset($_GET["action"]) && $_GET["action"] == "add"){
    $title = "Add Email Credit Package";
    require_once "themes/default/pages/add-email-credits-package.admin.page.php";
}else if(isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])){
    $title = "Edit Package";
    require_once "themes/default/pages/edit-email-credits-package.admin.page.php";
}else{
    $title = "Email Credits Package";
    require_once "themes/default/pages/email-credits.admin.page.php";
}