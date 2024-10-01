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
$membershipsController = new MembershipsController();
$flag = $membershipsController->addMembership();
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
            <span>Members</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <a href="memberships.php" class="text-primary hover:underline">Memberships</a>
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
                        <label for="noticeContent">Membership Title</label>
                        <input type="text" class="form-input" name="membership_title" placeholder="Enter membership title">
                        <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Sales Commission</label>
                        <input type="number" step="0.1" class="form-input" name="sales_commission" placeholder="Sales commission (percentage)">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Click Commission</label>
                        <input type="number" step="0.1" class="form-input" name="clicks_commission" placeholder="Click commision">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Timer (Seconds)</label>
                        <input type="number" step="0.1" class="form-input" name="timer_seconds" placeholder="Link click timer">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Daily Email Sending Limits</label>
                        <input type="number" class="form-input" name="email_sending_limit" placeholder="Daily Email Sending Limits">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Membership Price</label>
                        <input type="number" step="0.1" class="form-input" name="price" placeholder="Membership Price">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Bonus Email Credits</label>
                        <input type="number" step="0.1" class="form-input" name="bonus_email_credits" placeholder="Bonus Email Credits">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Bonus Text Ad Credits</label>
                        <input type="number" step="0.1" class="form-input" name="bonus_text_ad_credits" placeholder="Bonus Text Ad Credits">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Bonus Banner Credits</label>
                        <input type="number" step="0.1" class="form-input" name="bonus_banner_credits" placeholder="Bonus Banner Credits">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Credits Per Click</label>
                        <input type="number" class="form-input" name="credits_per_click" placeholder="Credits per click">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Max Email Recipient</label>
                        <input type="number" class="form-input" name="max_recipient" placeholder="Max recipient">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Stripe Price ID</label>
                        <small>Add your stripe Price ID for enabling subscription, leave this empty if you don't have one</small>
                        <input type="text" class="form-input" name="stripe_price_id" placeholder="Price ID">
                    </div>
                    <div class="form-group">
                        <label for="exampleSelectGender">Is this membership active ?</label>
                        <select class="form-input" name="status">
                            <option value="1">Yes</option>
                            <option value="2">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleSelectGender">Want to hide this from store page ?</label>
                        <select class="form-input" name="hidden">
                            <option value="1">Yes</option>
                            <option value="2">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleSelectGender">Subscription Type</label>
                        <select class="form-input" name="subscription_type">
                            <option value="1">Free</option>
                            <option value="2">Monthly</option>
                            <option value="3">Yearly</option>
                            <option value="4">Lifetime</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleSelectGender">Enable ChatGPT for this Membership ?</label>
                        <select class="form-input" name="chat_gpt_access">
                            <option value="1">Yes</option>
                            <option value="2">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">ChatGPT Prompt Limit</label>
                        <small>Limit to use ChatGPT per month. If you enter 10 then this membership's user can use ChatGPT 10 times per month.</small>
                        <input type="number" class="form-input" name="chat_gpt_prompt_limit" placeholder="Max prompt for ChatGPT response">
                    </div>
                    <button type="submit" class="btn btn-primary mt-6">Add</button>
                </form>
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