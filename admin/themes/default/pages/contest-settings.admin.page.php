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
$contestController = new ContestSettingsController();
$flag = $contestController->updateContestSettings($title);
$contestSettings = $contestController->getContestSettings($title);
$adminController->adminCSRFTokenGen();
?>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h2 class="text-xl"><?= $title ?></h2>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="index.php" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Notice & Contests</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">
            <div class="mb-5">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="exampleSelectGender">Enable Contest</label>
                        <select class="form-input" name="status">
                            <option value="1" <?= $contestSettings["status"] == 1 ? "selected" : "" ?>>Yes</option>
                            <option value="2" <?= $contestSettings["status"] == 2 ? "selected" : "" ?>>No</option>
                        </select>
                        <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                    </div>
                    <div class="form-group">
                        <label for="exampleSelectGender">Start Date</label>
                        <input type="date" class="form-input" name="start_date" placeholder="Start date" value="<?= $contestSettings["start_date"] ?>">
                    </div>
                    <div class="form-group">
                        <label for="exampleSelectGender">End Date</label>
                        <input type="date" class="form-input" name="end_date" value="<?= $contestSettings["end_date"] ?>" placeholder="End date">
                    </div>
                    <br>
                    <span class="badge bg-danger">First Prizes</span>

                    <div class="form-group">
                        <label for="noticeContent">Credits</label>
                        <input type="number" class="form-input" name="first_prize_credits" value="<?= $contestSettings["first_prize_credits"] ?>" placeholder="Enter credits amount">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Banner Credits</label>
                        <input type="number" class="form-input" name="first_prize_banner_credits" value="<?= $contestSettings["first_prize_banner_credits"] ?>" placeholder="Enter credits amount">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Text Ad Credits</label>
                        <input type="number" class="form-input" name="first_prize_text_credits" value="<?= $contestSettings["first_prize_text_credits"] ?>" placeholder="Enter credits amount">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Money Rewards</label>
                        <input type="number" class="form-input" name="first_prize_money" value="<?= $contestSettings["first_prize_money"] ?>" placeholder="Enter amount">
                    </div>
                    <span class="badge bg-info">Second Prizes</span>

                    <div class="form-group">
                        <label for="noticeContent">Credits</label>
                        <input type="number" class="form-input" name="second_prize_credits" value="<?= $contestSettings["second_prize_credits"] ?>" placeholder="Enter credits amount">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Banner Credits</label>
                        <input type="number" class="form-input" name="second_prize_banner_credits" value="<?= $contestSettings["second_prize_banner_credits"] ?>" placeholder="Enter credits amount">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Text Ad Credits</label>
                        <input type="number" class="form-input" name="second_prize_text_credits" value="<?= $contestSettings["second_prize_text_credits"] ?>" placeholder="Enter credits amount">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Money Rewards</label>
                        <input type="number" class="form-input" name="second_prize_money" value="<?= $contestSettings["second_prize_money"] ?>" placeholder="Enter amount">
                    </div>
                    <span class="badge bg-dark">Third Prizes</span>

                    <div class="form-group">
                        <label for="noticeContent">Credits</label>
                        <input type="number" class="form-input" name="third_prize_credits" value="<?= $contestSettings["third_prize_credits"] ?>" placeholder="Enter credits amount">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Banner Credits</label>
                        <input type="number" class="form-input" name="third_prize_banner_credits" value="<?= $contestSettings["third_prize_banner_credits"] ?>" placeholder="Enter credits amount">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Text Ad Credits</label>
                        <input type="number" class="form-input" name="third_prize_text_credits" value="<?= $contestSettings["third_prize_text_credits"] ?>" placeholder="Enter credits amount">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Money Rewards</label>
                        <input type="number" class="form-input" name="third_prize_money" value="<?= $contestSettings["third_prize_money"] ?>" placeholder="Enter amount">
                    </div>
                    <button type="submit" class="btn btn-primary mt-6">Update</button>
                </form>
            </div>
        </div>
        <div class="panel">
            <h2 style="font-size: 20px;">Leaderboard</h2>

            <?php
            if ($title == "Activity Contest") {
                $activityContestController = new ActivityContestController();
                $contestInfo = $activityContestController->activityContestLeaderboard();
                $countTitle = "Total Clicks";

            ?>
                <div class="table-responsive">
                    <table class="table-striped">

                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Username</th>
                                <th><?= isset($countTitle) ? $countTitle : "" ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($contestInfo["leaderboard"])) : ?>
                                <?php $counter = 1;
                                foreach ($contestInfo["leaderboard"] as $clickerData) : ?>
                                    <tr>
                                        <td><?= $counter; ?></td>
                                        <td><?= $clickerData["username"] ?></td>
                                        <td><?= $clickerData["total_clicks"] ?></td>
                                    </tr>
                                <?php $counter++;
                                endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php
            } else if ($title == "Referral Contest") {
                $referralContestController = new ReferralContestController();
                $contestInfo = $referralContestController->referralContestLeaderboard();
                $countTitle = "Total Referrals";
            ?>
                <div class="table-responsive">
                    <table class="table-striped">

                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Username</th>
                                <th><?= isset($countTitle) ? $countTitle : "" ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($contestInfo["leaderboard"])) : ?>
                                <?php $counter = 1;
                                foreach ($contestInfo["leaderboard"] as $referrerData) : ?>
                                    <tr>
                                        <td><?= $counter; ?></td>
                                        <td><?= $referrerData["referrer"] ?></td>
                                        <td><?= $referrerData["total_referrals"] ?></td>
                                    </tr>
                                <?php $counter++;
                                endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php
            } else if ($title == "Sales Contest") {
                $countTitle = "Total Sales (Amount)";
                $salesContestController = new SalesContestController();
                $contestInfo = $salesContestController->salesContestLeaderboard();
            ?>
                <div class="table-responsive">
                    <table class="table-striped">

                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Username</th>
                                <th><?= isset($countTitle) ? $countTitle : "" ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($contestInfo["leaderboard"])) : ?>
                                <?php $counter = 1;
                                foreach ($contestInfo["leaderboard"] as $soldData) : ?>
                                    <tr>
                                        <td><?= $counter; ?></td>
                                        <td><?= $soldData["affiliate_username"] ?></td>
                                        <td>$<?= $soldData["total_sold"] ?></td>
                                    </tr>
                                <?php $counter++;
                                endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php
            }
            ?>
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