<?php
$title = "Admin Ads List";
if(isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])){
    $title = "Edit Admin Ad";
    require_once "themes/default/pages/update-admin-ads.admin.page.php";
}else{
    require_once "themes/default/pages/admin-ads.admin.page.php";
}