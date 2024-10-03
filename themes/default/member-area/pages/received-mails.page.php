<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
// $receivedMails = new UnreadMailsController();
// $receivedMailList = $receivedMails->receivedMailsList($userInfo["username"]);
$receivedMails = new ReceivedMailsController();
$receivedMailList = $receivedMails->receivedMailsList($userInfo["username"]);
?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <?php require_once "themes/default/member-area/incs/sidebar-ads.inc.php"; ?>
            <div class="col-xl-9">
                <?php if (isset($flag) && isset($flag["success"])) : ?>
                <?php if ($flag["success"] == true) : ?>
                <div class="alert alert-success"><?= $flag["message"] ?></div>
                <?php else : ?>
                <div class="alert alert-danger"><?= $flag["message"] ?></div>
                <?php endif; ?>
                <?php endif; ?>
                <div class="alert alert-info">Total Received Mails :
                    <?= $receivedMails->totalReceivedMails($username) ?>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <!-- <table class="table table-striped">
                            <thead>
                                <td>Subject</td>
                                <td>Sending Date</td>
                                <td></td>
                            </thead> -->
                        <div class="navbar-nav flex-row d-flex justify-content-between border border-secondary item">
                            <div class="col-1"></div>
                            <div class="col-8">Subject</div>
                            <div class="col-3">Sending Date</div>
                        </div>
                        <?php if (!empty($receivedMailList)) : ?>
                        <?php foreach ($receivedMailList as $index => $receivedMailDetails) : 
                            $profileSection = '<table cellpadding="0" cellspacing="0" width="600" border="0" style="min-width: 600px; width: 100%;" dir="ltr" class="st-Copy st-Copy--caption st-Width st-Width--mobile">
                                                    <tbody>
                                                        <tr>
                                                            <td height="156" width="252" valign="bottom" align="right" style="background-color: #f9f9f9; border: 0; border-collapse: collapse; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; font-size: 0; line-height: 0px; mso-line-height-rule: exactly; background-size: 100% 100%; border-top-left-radius: 5px;" class="Header-left Target"><a rel="noopener" href="https://i-lovetraffic.online" style="-webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; outline: 0; text-decoration: none;"> <img height="156" width="252" alt="" src="https://stripe-images.s3.amazonaws.com/notifications/hosted/20180110/Header/Left.png" style="display: block; border: 0; line-height: 100%; width: 100%;"> </a></td>
                                                            <td height="156" valign="bottom" align="center" style="background-color: #f9f9f9; border: 0; border-collapse: collapse; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; font-size: 0; line-height: 0px; mso-line-height-rule: exactly; background-size: 100% 100%; width: 96px !important;" class="Header-icon Target"><a rel="noopener" href="https://i-lovetraffic.online" style="-webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; outline: 0; text-decoration: none;"> <img height="156" width="96" alt="" src='.$membersController->gravatar($membersController->userInfoByUsername($receivedMailDetails['sender_username'])['email'] , $siteSettingsData['installation_url']).' style="display: block; border: 0; height: 96px; border-radius: 100%"> </a></td>
                                                            <td height="156" width="252" valign="bottom" align="left" style="background-color: #f9f9f9; border: 0; border-collapse: collapse; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; font-size: 0; line-height: 0px; mso-line-height-rule: exactly; background-size: 100% 100%; border-top-right-radius: 5px;" class="Header-right Target"><a rel="noopener" href="https://i-lovetraffic.online" style="-webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; outline: 0; text-decoration: none;"> <img height="156" width="252" alt="" src="https://stripe-images.s3.amazonaws.com/notifications/hosted/20180110/Header/Right.png" style="display: block; border: 0; line-height: 100%; width: 100%;"> </a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table cellpadding="0" cellspacing="0" width="600" border="0" style="min-width: 600px; background-color: #ffffff; width: 100%;" class="st-Copy st-Copy--caption st-Width st-Width--mobile">
                                                    <tbody>
                                                        <tr>
                                                            <td align="center" style="border: 0; border-collapse: collapse; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; width: 472px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Ubuntu, sans-serif; mso-line-height-rule: exactly; vertical-align: middle; color: #32325d; font-size: 24px; line-height: 32px;" class="Content Title-copy Font Font--title">
                                                                '.$membersController->userInfoByUsername($receivedMailDetails['sender_username'])['first_name'].' '.$membersController->userInfoByUsername($receivedMailDetails['sender_username'])['last_name'].'
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height="12" colspan="3" style="border: 0; margin: 0; padding: 0; font-size: 1px; line-height: 1px; mso-line-height-rule: exactly;" class="st-Spacer st-Spacer--stacked">
                                                                <div class="st-Spacer st-Spacer--filler">&nbsp;</div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>';
                    

                        ?>
                        <div class="nav-item">
                            <a class="nav-link menu-link" href="#receivedEmail<?php echo $index;?>" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="receivedEmail<?php echo $index;?>">
                                
                                <div class="nav-item flex-row d-flex justify-content-between border border-primary item">
                                    <div class="col-1"><div class="d-flex align-items-center text-primary"
                                            href="<?= $siteSettingsData['installation_url'] . 'email-credits.php?type=email&id=' . $receivedMailDetails['credit_key'] . '&username=' . $username ?>"
                                            target="_blank"><i class="ri-mail-send-line"></i>View</div></div>
                                    <div class="col-8">
                                        <?= str_ireplace("{LASTNAME}", $userInfo["last_name"], str_ireplace("{FIRSTNAME}", $userInfo["first_name"], base64_decode($receivedMailDetails["email_subject"]))) ?>
                                    </div>
                                    <div class="col-3"><?= date("d M, Y H:i:s", $receivedMailDetails["sending_time"]) ?></div>
                                </div>
                            </a>
                            <div class="collapse menu-dropdown" id="receivedEmail<?php echo $index;?>">
                                <div class="p-3">
                                    <?php 
                                        $memberDetails = $userInfo;
                                        $emailBody = str_ireplace("{LASTNAME}", $userInfo["last_name"], str_ireplace("{FIRSTNAME}", $userInfo["first_name"], htmlspecialchars_decode(base64_decode($receivedMailDetails["email_body"])))); 
                                        $emailBody = $profileSection.$emailBody;
                                        $emailBody .= "<br><br>";
                                        $emailBody .= "Please click on the link below to get credit<br>";
                                        $emailBody .= "<a target='_blank' href='{$siteSettingsData['installation_url']}/email-credits.php?type=email&id={$receivedMailDetails['credit_key']}&username={$memberDetails['username']}'>";
                                        $emailBody .= "{$siteSettingsData['installation_url']}/email-credits.php?type=email&id={$receivedMailDetails['credit_key']}&username={$memberDetails['username']}</a>";
                                        $emailBody .= "<br>";
                                        $emailBody .= "<br>";
                                        $emailBody .= "<hr>";
                                        $emailBody .= "<br>";
                                        $emailBody .= "<br>";
                                        $emailBody .= "<br>";
                                        $emailBody .= "Is there anything wrong in the email? Click on the link below to report it.<br>";
                                        $emailBody .= "<a target='_blank' href='{$siteSettingsData['installation_url']}/email-report.php?id={$receivedMailDetails['id']}&report-key={$memberDetails['email_report_key']}&username={$memberDetails['username']}'>";
                                        $emailBody .= "{$siteSettingsData['installation_url']}/email-report.php?id={$receivedMailDetails['id']}&report-key={$memberDetails['email_report_key']}&username={$memberDetails['username']}</a>";
                                        $emailBody .= "<br><br>";
                                        $emailBody .= "<br>";
                                        $emailBody .= "<br>";
                                        $emailBody .= "Please click the following link to unsubscribe from our emails.<br>";
                                        $emailBody .= "<a target='_blank' href='{$siteSettingsData['installation_url']}/unsubscribe.php?unsubscribe={$memberDetails['account_activation_key']}&username={$memberDetails['username']}'>{$siteSettingsData['installation_url']}/unsubscribe.php?unsubscribe={$memberDetails['account_activation_key']}&username={$memberDetails['username']}</a>";
                                        $emailBody .= "<br>";
                                        $emailBody .= "<br>";
                                        $emailBody .= "©" . date("Y");
                                        $emailBody .= " " . $siteSettingsData['site_title'];
                                        $emailBody .= " | Powered By i-lovetraffic.online 1315 Piedmont Rd #32896, San Jose, CA 95132";
                                        echo $emailBody;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <!-- </table> -->
                    </div>
                    <?= $receivedMails->receivedMailsPagination($username) ?>
                </div>

                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
            </div>
        </div>

    </div>

    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>