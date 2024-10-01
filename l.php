<?php
ob_start();
require_once "modules/nsms-link-tracker/LinkTracker.php";
$linkTrackerController = new LinkTracker();
if (isset($_GET["l"]) && !empty($_GET["l"])) {
    $linkTrackerController->trackingSystem();
} else {
    header("Location: index.php");
}
