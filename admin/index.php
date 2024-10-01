<?php
$title = "Dashboard";
if(isset($_GET["action"]) && $_GET["action"] == "email"){
    $title = "Change Email";
    require_once "themes/default/pages/change-admin-email.admin.page.php";
}elseif(isset($_GET["action"]) && $_GET["action"] == "password"){
    $title = "Update Password";
    require_once "themes/default/pages/change-admin-password.admin.page.php";
}else{
    require_once "themes/default/pages/dashboard.admin.page.php";
}