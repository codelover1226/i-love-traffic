<?php
$title = "Shorten Links";
if (isset($_GET["details"]) && isset($_GET["user"])) {
    $title = "Link Details";
    require_once "themes/default/pages/shorten-link-details.admin.page.php";
} else if (isset($_GET["action"]) && $_GET["action"] == "settings") {
    $title = "Link Tracker Settings";
    require_once "themes/default/pages/update-link-tracker-settings.admin.page.php";
} else {
    require_once "themes/default/pages/shorten-links.admin.page.php";
}
