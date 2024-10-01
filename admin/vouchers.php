<?php
$title = "Vouchers";
if (isset($_GET["action"]) && $_GET["action"] == "edit" &&  isset($_GET["voucher"]) && !empty($_GET["voucher"])) {
    $title = "Edit Voucher";
    require_once "themes/default/pages/edit-voucher.admin.page.php";
} else if (isset($_GET["action"]) && $_GET["action"] == "history") {
    $title = "Voucher Used History";
    require_once "themes/default/pages/voucher-history.admin.page.php";
} else {
    require_once "themes/default/pages/vouchers.admin.page.php";
}
