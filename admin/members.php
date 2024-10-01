<?php
if(isset($_GET["action"]) && $_GET["action"] == "countries"){
    $title = "Member Countries";
    require_once "themes/default/pages/member-countries.admin.page.php";
}else if(isset($_GET["action"]) && $_GET["action"] == "search"){
    $title = "Search Member";
    require_once "themes/default/pages/member-search.admin.page.php";
}else if(isset($_GET["action"]) && $_GET["action"] == "banned"){
    $title = "Banned Members";
    require_once "themes/default/pages/members-by-status.admin.page.php";
}else if(isset($_GET["action"]) && $_GET["action"] == "awaiting-activation"){
    $title = "Awaiting Activation Members";
    require_once "themes/default/pages/members-by-status.admin.page.php";
}else if(isset($_GET["action"]) && $_GET["action"] == "unsubscribed"){
    $title = "Unsubscribed Members";
    require_once "themes/default/pages/members-by-status.admin.page.php";
}else if(isset($_GET["action"]) && $_GET["action"] == "more" && isset($_GET["username"]) && !empty($_GET["username"])){
    $title = "Member Details";
    require_once "themes/default/pages/member-details.admin.page.php";
    
}else if(isset($_GET["action"]) && $_GET["action"] == "edit" && isset($_GET["username"]) && !empty($_GET["username"])){
    $title = "Edit Member's Account";
    require_once "themes/default/pages/edit-member-account.admin.page.php";
    
}else if(isset($_GET["action"]) && $_GET["action"] == "change-password" && isset($_GET["username"]) && !empty($_GET["username"])){
    $title = "Edit Member's Account Password";
    require_once "themes/default/pages/change-member-password.admin.page.php";
    
}else{
    $title = "Member List";
    require_once "themes/default/pages/members.admin.page.php";
}