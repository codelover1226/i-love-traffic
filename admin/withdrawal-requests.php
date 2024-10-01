<?php
if(isset($_GET["action"]) && $_GET["action"] == "paid"){
    $title = "Paid Withdrawal Requests";
    require_once "themes/default/pages/paid-withdrawal-requests.admin.page.php";
}else{
    $title = "Pending Withdrawal Requests";
    require_once "themes/default/pages/withdrawal-requests.admin.page.php";
}