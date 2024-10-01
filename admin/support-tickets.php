<?php
$title = "Support Tickets";
if (isset($_GET["details"]) && !empty($_GET["details"]) && is_numeric($_GET["details"])) {
    $title = "Support Ticket Details";
    require_once "themes/default/pages/support-ticket-details.admin.page.php";
} else {
    require_once "themes/default/pages/support-tickets.admin.page.php";
}
