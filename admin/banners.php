<?php
$title = "Promotional Banners";
if(isset($_GET["action"]) && $_GET["action"] == "add"){
    $title = "Upload Promotional Banner";
    require_once "themes/default/pages/add-banner.admin.page.php";
}else{
    require_once "themes/default/pages/banners.admin.page.php";
}
