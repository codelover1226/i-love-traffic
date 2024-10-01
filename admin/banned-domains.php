<?php
if(isset($_GET["action"]) && $_GET["action"] == "add"){
    $title = "Add New Domain to Banned list";
    require_once "themes/default/pages/add-banned-domain.admin.page.php";
}else{
    $title = "Banned Domains";
    require_once "themes/default/pages/banned-domains.admin.page.php";
}