<?php


$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";

if (isset($_GET["open"])) {
    $flag = $supportTicketsController->userOpenTicket($userInfo["username"]);
} else if ($_GET["close"]) {
    $flag = $supportTicketsController->userCloseTicket($userInfo["username"]);
}
$supportTickets = $supportTicketsController->userTicketList($userInfo["username"]);
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
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <div class="card-header bg-transparent border-primary">
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Support Tickets</h5>
                            <div class="card-body">
                                <p class="card-text">
                                <table class="table">
                                    <thead>
                                        <td>Ticket Title</td>
                                        <td>Date</td>
                                        <td>Status</td>
                                        <td></td>
                                    </thead>
                                    <?php if (!empty($supportTickets)) : ?>
                                        <?php foreach ($supportTickets as $ticketData) : ?>
                                            <tr>

                                                <td><?= $ticketData["ticket_title"] ?></td>
                                                <td><?= date("d M, Y", $ticketData["ticket_timestamp"]) ?></td>
                                                <td>
                                                    <?php if ($ticketData["ticket_status"] == 1) : ?>
                                                        <span class="badge border border-primary text-primary"><?= $supportTicketsController->ticketStatus($ticketData["ticket_status"]) ?></span>
                                                    <?php elseif ($ticketData["ticket_status"] == 2) : ?>
                                                        <span class="badge border border-success text-primary"><?= $supportTicketsController->ticketStatus($ticketData["ticket_status"]) ?></span>
                                                    <?php else : ?>
                                                        <span class="badge border border-danger text-primary"><?= $supportTicketsController->ticketStatus($ticketData["ticket_status"]) ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="hstack flex-wrap gap-2 mb-3 mb-lg-0">
                                                        <a href="support-tickets.php?details=<?= $ticketData['id'] ?>" class="btn btn-soft-dark btn-border btn-sm">Details</a>
                                                        <?php if ($ticketData["ticket_status"] != 3) : ?>
                                                            <a href="support-tickets.php?close=<?= $ticketData['id'] ?>&token=<?= $membersController->getUserCSRFToken() ?>" class="btn btn-soft-danger btn-border btn-sm">Close</a>
                                                        <?php else : ?>
                                                            <a href="support-tickets.php?open=<?= $ticketData['id'] ?>&token=<?= $membersController->getUserCSRFToken() ?>" class="btn btn-soft-success btn-border btn-sm">Open</a>
                                                        <?php endif; ?>

                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </table>
                                </p>
                            </div>
                            <?= $supportTicketsController->userTicketPagination($username) ?>
                        </div>
                    </div>

                </div>
                <?php require_once "themes/default/member-area/incs/footer-ads.inc.php"; ?>
                
            </div>

        </div>

        <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>