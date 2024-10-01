<?php
require_once "load_classes.php";
$siteSettingsController = new SiteSettingsController();
$siteSettings = $siteSettingsController->getSettings();
$emailReportController = new EmailReportsController();
$flag = $emailReportController->reportEmail();
?>

<html>

<head>
    <title><?= $siteSettings["site_title"] ?></title>
</head>

<body>
    <div align="center">
        <h3>Report An Email</h3><br>
        <?php if (isset($flag) && isset($flag["message"])) : ?>
            <p style="font-weight: bold; font-size: 16px;"><?= $flag["message"] ?></p>
        <?php endif; ?>
    </div>

</body>

</html>