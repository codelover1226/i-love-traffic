<?php
/*
 *
 *
 *          Author          :   Noman Prodhan
 *          Email           :   hello@nomantheking.com
 *          Websites        :   www.nomantheking.com    www.nomanprodhan.com    www.nstechvalley.com
 *
 *
 */


$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}

require_once "themes/default/incs/header.theme.php";
$supportTicketsController = new SupportTicketsController();
$supportTicketDetails = $supportTicketsController->getTicketDetails($_GET["details"]);
if (!empty($supportTicketDetails)) {
    $flag = $supportTicketsController->createReply($ticketAuthorDetails, $siteSettingsData);
    $supportTicketReplies = $supportTicketsController->getTicketReplies($_GET["details"]);
    $membersController = new MembersController();
    $ticketAuthorDetails = $membersController->getUserDetails($supportTicketDetails["ticket_author_username"]);
    $siteSettingsController = new SiteSettingsController();
    $siteSettingsData = $siteSettingsController->getSettings();
    
}



$adminController->adminCSRFTokenGen();
?>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h2 class="text-xl"><?= $title ?></h2>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="index.php" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Supports</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="panel">
        <form action="support-tickets.php?details=<?= $supportTicketDetails['id'] ?>" method="POST">
            <div>
                <label for="ctnTextarea">Write a Reply</label>
                <input type="hidden" name="csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                <input type="hidden" name="ticket_id" value="<?= $supportTicketDetails['id'] ?>">
                <textarea name="reply" id="ctnTextarea" rows="3" class="form-textarea" placeholder="Enter your reply" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary !mt-6">Reply</button>
        </form>
    </div>
    <?php if (!empty($supportTicketDetails)) : ?>
        <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-1">
            <div class="panel">
                <div class="mb-5">
                    <?php if (!empty($supportTicketReplies)) : ?>
                        <?php foreach ($supportTicketReplies as $replyDetails) : ?>
                            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-1">
                                <div class="panel h-full">
                                    <div class="-m-5 mb-5 flex items-start border-b border-[#e0e6ed] p-5 dark:border-[#1b2e4b]">
                                        <div class="shrink-0 rounded-full ring-2 ring-white-light ltr:mr-4 rtl:ml-4 dark:ring-dark">
                                            <?php if ($replyDetails["reply_author"] == $supportTicketDetails["ticket_author_username"]) : ?>
                                                <img src="<?= $membersController->gravatar($ticketAuthorDetails['email'], $siteSettingsData['installation_url']) ?>" alt="image" class="h-10 w-10 rounded-full object-cover">
                                            <?php else : ?>
                                                <img src="../logo/logo_support_ticket.png" alt="image" class="h-10 w-10 rounded-full object-cover">

                                            <?php endif; ?>
                                        </div>
                                        <div class="font-semibold">
                                            <?php if ($replyDetails["reply_author"] == $supportTicketDetails["ticket_author_username"]) : ?>
                                                <h6><?= $supportTicketDetails["ticket_author_username"] ?> <span class="badge bg-primary"><?= $ticketAuthorDetails["membership_title"] ?></span></h6>
                                            <?php else : ?>
                                                <h6><?= $supportTicketDetails["ticket_author_username"] ?> <span class="badge bg-danger">Admin</span></h6>
                                            <?php endif; ?>
                                            <p class="mt-1 text-xs text-white-dark"><?= date("d, M Y", $supportTicketDetails["ticket_timestamp"]) ?></p>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="pb-8 text-white-dark">
                                            <?= $replyDetails["reply"] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-1">
                        <div class="panel h-full">
                            <div class="-m-5 mb-5 flex items-start border-b border-[#e0e6ed] p-5 dark:border-[#1b2e4b]">
                                <div class="shrink-0 rounded-full ring-2 ring-white-light ltr:mr-4 rtl:ml-4 dark:ring-dark">
                                    <img src="<?= $membersController->gravatar($ticketAuthorDetails['email'], $siteSettingsData['installation_url']) ?>" alt="image" class="h-10 w-10 rounded-full object-cover">
                                </div>
                                <div class="font-semibold">
                                    <h6><?= $supportTicketDetails["ticket_author_username"] ?> <span class="badge bg-primary"><?= $ticketAuthorDetails["membership_title"] ?></span></h6>
                                    <p class="mt-1 text-xs text-white-dark"><?= date("d, M Y", $supportTicketDetails["ticket_timestamp"]) ?></p>
                                </div>
                            </div>
                            <div>
                                <div class="pb-8 text-white-dark">
                                    <?= $supportTicketDetails["ticket_body"] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="flex items-center p-3.5 rounded text-primary bg-primary-light dark:bg-primary-dark-light">
            <span class="ltr:pr-2 rtl:pl-2">Couldn't find the ticket.</span>
        </div>
    <?php endif; ?>
</div>
<?php if (isset($flag) && isset($flag["success"])) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($flag["success"] == true) : ?>
                Swal.fire({
                    title: 'Success!',
                    text: '<?= addslashes($flag["message"]) ?>',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            <?php else : ?>
                Swal.fire({
                    title: 'Error!',
                    text: '<?= addslashes($flag["message"]) ?>',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>
        });
    </script>
<?php endif; ?>

<?php require_once "themes/default/incs/footer.theme.php"; ?>