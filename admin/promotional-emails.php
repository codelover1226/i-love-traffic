<?php
$title = "Promotional Emails";
if (isset($_GET["edit"]) && !empty($_GET["edit"]) && is_numeric($_GET["edit"])) {
    $title = "Edit Promotional Email";
    require_once "themes/default/pages/edit-promotional-email.admin.page.php";
}else if($_GET["action"] == "add"){
    $title = "Add Promotional Email";
    require_once "themes/default/pages/add-promotional-email.admin.page.php";
} else {
    require_once "themes/default/pages/promotional-emails.admin.page.php";
}
