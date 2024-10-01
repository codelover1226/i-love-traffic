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
$membersController = new MembersController();
$memberCountries = $membersController->memberCountries();
$adminController->adminCSRFTokenGen();
?>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h2 class="text-xl"><?= $title ?></h2>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="index.php" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Mailer & Members</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <a href="members.php" class="text-primary hover:underline">Members</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-1">
        <div class="panel">
            <div class="relative inline-flex align-middle">
                <a href="members.php"><button type="button" class="btn btn-dark ltr:rounded-r-none rtl:rounded-l-none">All Members</button></a>
                <a href="members.php?action=countries"><button type="button" class="btn btn-success rounded-none">Countries</button></a>
                <a href="members.php?action=search"><button type="button" class="btn btn-primary rounded-none">Search</button></a>
                <a href="members.php?action=banned"><button type="button" class="btn btn-info rounded-none">Banned Members</button></a>
                <a href="members.php?action=unsubscribed"><button type="button" class="btn btn-danger ltr:rounded-l-none rtl:rounded-r-none">Unsubscribed Members</button></a>
            </div>
            <br><br>
            <div class="mb-5">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Total Members</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($memberCountries)) : ?>
                                <?php foreach ($memberCountries as $country) : ?>
                                    <tr>
                                        <td><?= $country["country"] ?></td>
                                        <td><?= $country["total_members"] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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