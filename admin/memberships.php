<?php
$title = "Memberships";
if(isset($_GET["action"]) && $_GET["action"] == "add"){
    $title = "Add New Membership Package";
    require_once "themes/default/pages/add-membership.admin.page.php";
}elseif(isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])){
    $title = "Edit Membership Package";
    require_once "themes/default/pages/edit-membership.admin.page.php";
}else{
    require_once "themes/default/pages/memberships.admin.page.php";
}