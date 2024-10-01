<?php
if(isset($_GET["action"]) && $_GET["action"] == "add"){
    $title = "Affiliate Splash Pages";
    require_once "themes/default/pages/add-splash-page.admin.page.php";
}else if(isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])){
    $title = "Edit Splash Pages";
    require_once "themes/default/pages/edit-splash-page.admin.page.php";
}else{
    $title = "Affiliate Splash Pages";
    require_once "themes/default/pages/splash-pages.admin.page.php";
}