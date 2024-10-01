<?php
if(isset($_GET["action"]) && $_GET["action"] == "send"){
    $title = "Send New Mass Mail";
    require_once "themes/default/pages/add-mass-mail.admin.page.php";
}else{
    $title = "Mass Mails";
    require_once "themes/default/pages/mass-mails.admin.page.php";
}