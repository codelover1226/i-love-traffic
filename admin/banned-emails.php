<?php
if (isset($_GET["action"]) && $_GET["action"] == "add") {
    $title = "Add Banned Email";
    require_once "themes/default/pages/add-banned-email.admin.page.php";
} else {
    $title = "Banned Emails";
    require_once "themes/default/pages/banned-emails.admin.page.php";
}
