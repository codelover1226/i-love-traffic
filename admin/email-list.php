<?php
if(isset($_GET["more"]) && !empty($_GET["more"]) && is_numeric($_GET["more"])){
    $title = "Email Details";
    require_once "themes/default/pages/email-details.admin.page.php";
}else{
    $title = "Emails";
require_once "themes/default/pages/emails.admin.page.php";
}