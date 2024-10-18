<?php
$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    header("Location: / ");
    exit;
}
if (file_exists("load_classes.php")) {
    require_once "load_classes.php";
} else {
    if (file_exists("../load_classes.php")) {
        require_once "../load_classes.php";
    }
}
class MembersController extends Controller
{
    private $model;
    private $firstNameMaxLen = 20;
    private $lastNameMaxLen = 20;
    private $passwordMinLen = 8;
    private $usernameMinLen = 4;
    private $usernameMaxLen = 15;
    private $passwordSalt = "nTk+S";
    public function __construct()
    {
        $this->model = new MembersModel();
    }
    private function disallowUsernames()
    {
        return ["username", "admin", "administrator", "test", "testing", "owner", "fuck", "sex", "fucker"];
    }
    public function membersList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalMembers();
            $total_offset = ceil($total / 30);
            if ($_GET["page"] - 1 < 0) {
                $offset = 0;
            } else {
                if ($total_offset < $_GET["page"] - 1) {
                    $offset = 0;
                } else {
                    $offset = ($_GET["page"] - 1) * 30;
                }
            }
        }
        return $this->model->membersList(30, $offset);
    }
    public function membersPagination()
    {
        return $this->pagination(30, $this->totalMembers(), "members.php");
    }
    public function totalMembers()
    {
        return $this->model->totalMembers();
    }
    public function userInfoByUsername($username)
    {
        return $this->model->userInfoByUsername($username);
    }
    public function userInfoByEmail($email)
    {
        return $this->model->userInfoByEmail($email);
    }
    public function addBalance($username, $amount)
    {
        return $this->model->addBalance($username, $amount);
    }
    public function totalAffiliateBalance()
    {
        return $this->model->totalAffiliateBalance()["total_affiliate_balance"];
    }
    public function usernameCheck($string)
    {
        if (preg_match("/^[A-Za-z0-9_]+\$/", $string)) {
            return false;
        }
        return true;
    }
    public function stringCheck($string)
    {
        if (preg_match("/^[A-Za-z ]+\$/", $string)) {
            return false;
        }
        return true;
    }
    public function createNewAccount()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm_password"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (empty($_POST["username"]) || empty($_POST["first_name"]) || empty($_POST["last_name"]) || empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["confirm_password"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["csrf_token"] != $this->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                return ["success" => false, "message" => "Please enter a valid email address."];
            }
            if ($this->firstNameMaxLen < strlen($_POST["first_name"])) {
                return ["success" => false, "message" => "First name is too long."];
            }
            if ($this->lastNameMaxLen < strlen($_POST["last_name"])) {
                return ["success" => false, "message" => "Last name is too long."];
            }
            if ($this->stringCheck($_POST["first_name"])) {
                return ["success" => false, "message" => "Invalid first name."];
            }
            if ($this->stringCheck($_POST["last_name"])) {
                return ["success" => false, "message" => "Invalid last name."];
            }
            if (strlen($_POST["password"]) < $this->passwordMinLen) {
                return ["success" => false, "message" => "Password is too short. Password must be minimum " . $this->passwordMinLen . " characters."];
            }
            if (strlen($_POST["username"]) < $this->usernameMinLen) {
                return ["success" => false, "message" => "Username is too short. Username must be minimum " . $this->usernameMinLen . " characters."];
            }
            if ($this->usernameCheck($_POST["username"])) {
                return ["success" => false, "message" => "You can't use special character or space in username."];
            }
            if ($this->usernameMaxLen <= strlen($_POST["username"])) {
                return ["success" => false, "message" => "Username is too long. Username can be maximum " . $this->usernameMaxLen . " characters."];
            }
            if ($_POST["password"] != $_POST["confirm_password"]) {
                return ["success" => false, "message" => "Password didn't match"];
            }
            if (preg_match("/\\s/", $_POST["username"])) {
                return ["success" => false, "message" => "Space not allowed in username."];
            }
            if ($this->userInfoByUsername($_POST["username"])) {
                return ["success" => false, "message" => "Username already exist"];
            }
            if ($this->userInfoByEmail($_POST["email"])) {
                return ["success" => false, "message" => "Email already exist"];
            }
            if (in_array($_POST["username"], $this->disallowUsernames())) {
                return ["success" => false, "message" => "Sorry, you can't use that username."];
            }
            if (!isset($_POST["tos"])) {
                return ["success" => false, "message" => "You have to agree with our terms of services."];
            }
            if ($_POST["tos"] != "agree") {
                return ["success" => false, "message" => "You have to agree with our terms of services."];
            }
            $bannedEmailController = new BannedEmailsController();
            if ($bannedEmailController->bannedEmailDetails($_POST["email"])) {
                return ["success" => false, "message" => "The email is in our banned list."];
            }
            $siteSettingsController = new SiteSettingsController();
            $siteSettigsData = $siteSettingsController->getSettings();
            if ($siteSettigsData["member_registration"] != 1) {
                return ["success" => false, "message" => "Member registration has been disabled."];
            }
            if ($siteSettigsData["anti_cheat_system"] == 1 && $this->userIPInfo($_SERVER["REMOTE_ADDR"])) {
                return ["success" => false, "message" => "Someone already created an account from this IP " . $_SERVER["REMOTE_ADDR"] . " . We allow only one account per IP address."];
            }
            // if (!empty($siteSettigsData["google_captcha_public_key"]) && !empty($siteSettigsData["google_captcha_private_key"])) {
            //     if (isset($_POST["g-recaptcha-response"])) {
            //         $captcha = $_POST["g-recaptcha-response"];
            //         $secretKey = $siteSettigsData["google_captcha_private_key"];
            //         $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . urlencode($secretKey) . "&response=" . urlencode($captcha);
            //         $response = file_get_contents($url);
            //         $responseKeys = json_decode($response, true);
            //         if (!$responseKeys["success"]) {
            //             return ["success" => false, "message" => "Invalid captcha."];
            //         }
            //     } else {
            //         return ["success" => false, "message" => "You forgot the captcha."];
            //     }
            // }
            $referrer = "";
            if (isset($_COOKIE["nsms_affiliate"]) && $_COOKIE["nsms_affiliate"] != $_POST["username"]) {
                $referrer = $_COOKIE["nsms_affiliate"];
            }
            $api_link = "http://ip-api.com/php/" . $_SERVER["REMOTE_ADDR"];
            if (!function_exists("curl_init")) {
                exit("CURL is not installed!");
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);
            $ip_info = @unserialize($output);
            $user_country = "";
            if ($ip_info && $ip_info["status"] == "success") {
                $user_country = $ip_info["country"];
            }
            $accountActivationKey = md5(uniqid("ntkS"));
            $activationLink = $siteSettigsData["installation_url"] . "login.php?action=activate&user=" . $_POST["username"] . "&key=" . $accountActivationKey;
            $activationMassege = "Dear " . $_POST["first_name"];
            $activationMassege .= "Thanks for registering with us. Your account is almost ready for use. All you have to do is activate your account. <br>";
            $activationMassege .= "Click the button below to activate your account.<br>";
            $activationMassege .= "If the button doesn't work, copy and paste this link into your browser: " . $activationLink . "<br>";
            $this->model->addNewMember(["username" => $_POST["username"], "first_name" => $_POST["first_name"], "last_name" => $_POST["last_name"], "email" => $_POST["email"], "phone" => "", "country" => $user_country, "telegram" => "", "skype" => "", "password" => password_hash($this->passwordSalt . $_POST["password"], PASSWORD_BCRYPT), "password_reset" => md5(uniqid("nTkS")), "password_reset_request_time" => 0, "account_activation_key" => $accountActivationKey, "account_activation_request_time" => time(), "account_status" => 0, "vacation_end_time" => time(), "membership" => 1, "membership_end_time" => "Lifetime", "credits" => 0, "banner_credits" => 0, "text_ad_credits" => 0, "balance" => 0, "membership_end_notification" => 0, "join_timestamp" => time(), "last_login_timestamp" => 0, "registration_ip" => $_SERVER["REMOTE_ADDR"], "paypal" => "", "btc_coinbase" => "", "skrill" => "", "transfer_wise" => "", "perfect_money" => "", "eth_wallet" => "", "referrer" => $referrer, "referral_link_clicks" => 0, "total_clicks" => 0, "unsubscribe_key" => md5(uniqid("ntkS-")), "email_report_key" => md5(uniqid("ntkS+")), "auto_email_subject" => "", "auto_email_body" => "", "auto_email_website" => "", "auto_email_status" => 2]);
            // SingleEmailSystem::sendEmail("no-reply@" . parse_url($siteSettigsData["installation_url"])["host"], $siteSettigsData["site_title"], $_POST["email"], $_POST["first_name"] . " " . $_POST["last_name"], "Activate your account", SystemEmailTemplate::emailTemplate($siteSettigsData["logo_link"], $siteSettigsData["installation_url"], "Account Activation", $activationMassege, $activationLink, "Activate Account"));
            // if (!empty($referrer)) {
            //     $referrerDetails = $this->getUserDetails($referrer);
            //     if (!empty($referrerDetails)) {
            //         $referrerEmailSubject = $siteSettigsData["site_title"] . " :: ";
            //         $referrerEmailSubject .= $_POST["first_name"] . " ";
            //         $referrerEmailSubject .= $_POST["last_name"];
            //         $referrerEmailSubject .= " has joined under your downline";
            //         $referrerUpdateMessage = "Dear " . $referrerDetails["first_name"] . " " . $referrerDetails["last_name"] . "<br>";
            //         $referrerUpdateMessage .= "A new member has joined " . $siteSettigsData["site_title"] . " using your affiliate link. <br>";
            //         $referrerUpdateMessage .= "Username :  " . $_POST["username"] . " <br>";
            //         $referrerUpdateMessage .= "First Name :  " . $_POST["first_name"] . " <br>";
            //         $referrerUpdateMessage .= "Last Name :  " . $_POST["last_name"] . " <br>";
            //         if ($referrerDetails["membership"] != 1) {
            //             $referrerUpdateMessage .= "You can use our affiliate messaging system to contact your downline member.";
            //         } else {
            //             $referrerUpdateMessage .= "<br>Unlock the power of our affiliate messaging system to seamlessly connect with your dynamic downline members. ";
            //             $referrerUpdateMessage .= "Elevate your communication game and boost your team's success! ";
            //             $referrerUpdateMessage .= "Upgrade today and take your membership to the next level. ";
            //             $referrerUpdateMessage .= "Don't miss out on this game-changing opportunity, because great connections lead to greater achievements!";
            //         }
            //         SingleEmailSystem::sendEmail("no-reply@" . parse_url($siteSettigsData["installation_url"])["host"], $siteSettigsData["site_title"], $referrerDetails["email"], $referrerDetails["first_name"] . " " . $referrerDetails["last_name"], $referrerEmailSubject, SystemEmailTemplate::emailTemplate($siteSettigsData["logo_link"], $siteSettigsData["installation_url"], "Downline Update", $referrerUpdateMessage, $siteSettigsData["installation_url"] . "/login.php", "Login To Your Account"));
            //     }
            // }
            return ["success" => true, "message" => "Your account has been created. We have sent you an activation link. Please check your inbox/spam."];
        }
    }
    public function generateUserCSRFToken()
    {
        $_SESSION["csrf_token"] = md5(uniqid("nTks"));
    }
    public function getUserCSRFToken()
    {
        return $_SESSION["csrf_token"];
    }
    public function getUserDetails($username)
    {
        return $this->model->getMemberDetails($username);
    }
    public function memberStatus()
    {
        return ["Inactive", "Active", "Banned", "Unsubscribed", "Vacation Mode"];
    }
    public function totalMemberByStatus($status)
    {
        return $this->model->totalMemberByStatus($status);
    }
    public function memberListByStatus($status)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalMembers();
            $total_offset = ceil($total / 30);
            if ($_GET["page"] - 1 < 0) {
                $offset = 0;
            } else {
                if ($total_offset < $_GET["page"] - 1) {
                    $offset = 0;
                } else {
                    $offset = ($_GET["page"] - 1) * 30;
                }
            }
        }
        return $this->model->getMemberListByStatus($status, 30, $offset);
    }
    public function memberPaginationByStatus($status, $limit, $base_url)
    {
        $total = $this->totalMemberByStatus($status);
        $total_offset = ceil($total / $limit);
        $current_page = 1;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] <= $total_offset) {
            $current_page = $_GET["page"];
        }
        if (1 < $total_offset) {
            echo "<nav aria-label=\"Page navigation example\"><ul class=\"pagination\">";
            if (1 < $current_page) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"" . $base_url . "&page=" . ($current_page - 1) . "\">Previous</a></li>";
            }
            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"#\">" . $current_page . "</a></li>";
            if ($current_page < $total_offset) {
                echo "<li class=\"page-item\"><a class=\"page-link\" href=\"" . $base_url . "&page=" . ($current_page + 1) . "\">Next</a></li>";
            }
            echo "</ul></nav>";
        }
    }
    public function banMemberAccount()
    {
        $adminController = new AdminController();
        if (isset($_GET["ban"]) && !empty($_GET["ban"]) && isset($_GET["token"]) && $_GET["token"] == $adminController->getAdminCSRFToken()) {
            if ($this->arrayCheck($_GET)) {
                return ["success" => false, "message" => "Sorry ! Array not allowed."];
            }
            $memberDetails = $this->userInfoByUsername($_GET["ban"]);
            if (empty($memberDetails)) {
                return ["success" => false, "message" => "Couldn't find the member"];
            }
            $this->model->updateMemberAccount(["account_status" => 2], $_GET["ban"]);
            return ["success" => true, "message" => "Member account has been banned."];
        }
    }
    public function activateMemberAccount()
    {
        $adminController = new AdminController();
        if (isset($_GET["activate"]) && !empty($_GET["activate"]) && isset($_GET["token"]) && $_GET["token"] == $adminController->getAdminCSRFToken()) {
            if ($this->arrayCheck($_GET)) {
                return ["success" => false, "message" => "Sorry ! Array not allowed."];
            }
            $memberDetails = $this->userInfoByUsername($_GET["activate"]);
            if (empty($memberDetails)) {
                return ["success" => false, "message" => "Couldn't find the member"];
            }
            $this->model->updateMemberAccount(["account_status" => 1], $_GET["activate"]);
            return ["success" => true, "message" => "Member account has been activated."];
        }
    }
    public function unsubscribeMemberAccount()
    {
        $adminController = new AdminController();
        if (isset($_GET["unsubscribe"]) && !empty($_GET["unsubscribe"]) && isset($_GET["token"]) && $_GET["token"] == $adminController->getAdminCSRFToken()) {
            if ($this->arrayCheck($_GET)) {
                return ["success" => false, "message" => "Sorry ! Array not allowed."];
            }
            $memberDetails = $this->userInfoByUsername($_GET["unsubscribe"]);
            if (empty($memberDetails)) {
                return ["success" => false, "message" => "Couldn't find the member"];
            }
            $this->model->updateMemberAccount(["account_status" => 3], $_GET["unsubscribe"]);
            return ["success" => true, "message" => "Member account has been unsubscribed."];
        }
    }
    public function totalMemberByMembership($membership)
    {
        return $this->model->totalMemberByMembership($membership);
    }
    public function changeMemberAccountPassword($username)
    {
        if (isset($_POST["password"]) && isset($_POST["confirm_password"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (empty($_POST["password"]) || empty($_POST["confirm_password"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            $adminController = new AdminController();
            if ($_POST["admin_csrf_token"] != $adminController->getAdminCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if ($_POST["password"] != $_POST["confirm_password"]) {
                return ["success" => false, "message" => "Password didn't match."];
            }
            if (strlen($_POST["password"]) < $this->passwordMinLen) {
                return ["success" => false, "message" => "Password is too short."];
            }
            $this->model->updateMemberAccount(["password" => password_hash($this->passwordSalt . $_POST["password"], PASSWORD_BCRYPT)], $username);
            return ["success" => true, "message" => "Member account's password has been changed."];
        }
    }
    public function memberCountries()
    {
        return $this->model->memberCountries();
    }
    public function searchMemberByUsername()
    {
        if (isset($_GET["username"])) {
            if (!empty($_GET["username"])) {
                return $this->model->searchMemberByUsername($_GET["username"]);
            }
        }
    }
    public function searchMemberByEmail()
    {
        if (isset($_GET["email"])) {
            if (!empty($_GET["email"])) {
                if (filter_var($_GET["email"], FILTER_VALIDATE_EMAIL)) {
                    return $this->model->searchMemberByEmail($_GET["email"]);
                }
            }
        }
    }
    public function recentMembers()
    {
        return $this->model->recentMembers(5);
    }
    public function updateMemberAccount($username)
    {
        if (isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["skype"]) && isset($_POST["telegram"]) && isset($_POST["paypal"]) && isset($_POST["btc_coinbase"]) && isset($_POST["skrill"]) && isset($_POST["transfer_wise"]) && isset($_POST["perfect_money"]) && isset($_POST["eth_wallet"]) && isset($_POST["membership"]) && isset($_POST["credits"]) && isset($_POST["banner_credits"]) && isset($_POST["text_ad_credits"]) && isset($_POST["balance"]) && isset($_POST["phone"]) && isset($_POST["admin_csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (empty($_POST["first_name"]) || empty($_POST["last_name"]) || empty($_POST["email"]) || empty($_POST["membership"]) || empty($_POST["admin_csrf_token"])) {
                return ["success" => false, "message" => "First and Last name, Email, Membership are required."];
            }
            if (!is_numeric($_POST["balance"]) || $_POST["balance"] < 0) {
                return ["success" => false, "message" => "Invalid balance."];
            }
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                return ["success" => false, "message" => "Invalid email."];
            }
            if ($this->firstNameMaxLen < strlen($_POST["first_name"])) {
                return ["success" => false, "message" => "First name is too long. You can enter maximum " . $this->firstNameMaxLen . " characters."];
            }
            if ($this->lastNameMaxLen < strlen($_POST["last_name"])) {
                return ["success" => false, "message" => "First name is too long. You can enter maximum " . $this->lastNameMaxLen . " characters."];
            }
            if (!empty($_POST["skype"]) && 150 < strlen($_POST["skype"])) {
                return ["success" => false, "message" => "Skype name is too long."];
            }
            if (!empty($_POST["telegram"]) && 150 < strlen($_POST["telegram"])) {
                return ["success" => false, "message" => "Telegram name is too long."];
            }
            if (!empty($_POST["paypal"]) && 150 < strlen($_POST["paypal"])) {
                return ["success" => false, "message" => "PayPal email is too long."];
            }
            if (!empty($_POST["btc_coinbase"]) && 150 < strlen($_POST["btc_coinbase"])) {
                return ["success" => false, "message" => "BTC/Coinbase wallet is too long."];
            }
            if (!empty($_POST["skrill"]) && 150 < strlen($_POST["skrill"])) {
                return ["success" => false, "message" => "Skrill is too long."];
            }
            if (!empty($_POST["transfer_wise"]) && 150 < strlen($_POST["transfer_wise"])) {
                return ["success" => false, "message" => "Transferwise is too long."];
            }
            if (!empty($_POST["perfect_money"]) && 150 < strlen($_POST["perfect_money"])) {
                return ["success" => false, "message" => "PerfectMoney is too long."];
            }
            if (!empty($_POST["eth_wallet"]) && 150 < strlen($_POST["eth_wallet"])) {
                return ["success" => false, "message" => "ETH Wallet address is too long."];
            }
            if (!empty($_POST["phone"]) && 30 < strlen($_POST["phone"])) {
                return ["success" => false, "message" => "Phone number is too long."];
            }
            if (!empty($_POST["paypal"]) && !filter_var($_POST["paypal"], FILTER_VALIDATE_EMAIL)) {
                return ["success" => false, "message" => "Invalid PayPal email."];
            }
            if (!empty($_POST["skrill"]) && !filter_var($_POST["skrill"], FILTER_VALIDATE_EMAIL)) {
                return ["success" => false, "message" => "Invalid Skrill email."];
            }
            if (!empty($_POST["transfer_wise"]) && !filter_var($_POST["transfer_wise"], FILTER_VALIDATE_EMAIL)) {
                return ["success" => false, "message" => "Invalid Transferwise email."];
            }
            if (!empty($_POST["perfect_money"]) && !filter_var($_POST["perfect_money"], FILTER_VALIDATE_EMAIL)) {
                return ["success" => false, "message" => "Invalid PerfectMoney email."];
            }
            $emailDetails = $this->userInfoByEmail($_POST["email"]);
            $userDetails = $this->userInfoByUsername($username);
            if ($_POST["email"] != $userDetails["email"] && !empty($emailDetails)) {
                return ["success" => false, "message" => "Email already exist for another user."];
            }
            $membershipController = new MembershipsController();
            $memberShipDetails = $membershipController->getMembershipDetails($_POST["membership"]);
            if (empty($memberShipDetails)) {
                return ["success" => false, "message" => "Invalid membership."];
            }
            $this->model->updateMemberDetails(["first_name" => $_POST["first_name"], "last_name" => $_POST["last_name"], "email" => $_POST["email"], "phone" => $_POST["phone"], "telegram" => $_POST["telegram"], "skype" => $_POST["skype"], "membership" => $_POST["membership"], "credits" => $_POST["credits"], "banner_credits" => $_POST["banner_credits"], "text_ad_credits" => $_POST["text_ad_credits"], "balance" => $_POST["balance"], "paypal" => $_POST["paypal"], "btc_coinbase" => $_POST["btc_coinbase"], "skrill" => $_POST["skrill"], "transfer_wise" => $_POST["transfer_wise"], "perfect_money" => $_POST["perfect_money"], "eth_wallet" => $_POST["eth_wallet"]], $username);
            return ["success" => true, "message" => "Account details has been updated."];
        }
    }
    public function newMemberToday()
    {
        return $this->model->newMemberToday();
    }
    public function totalUserReferrals($username)
    {
        return $this->model->totalReferrals($username);
    }
    public function deductMemberTextAdCredits($username, $amount)
    {
        $userDetails = $this->getUserDetails($username);
        if ($userDetails["text_ad_credits"] < $amount) {
            $amount = $userDetails["text_ad_credits"];
        }
        $this->model->deductMemberTextAdCredits($username, $amount);
    }
    public function deductMemberBannerAdCredits($username, $amount)
    {
        $userDetails = $this->getUserDetails($username);
        if ($userDetails["banner_credits"] < $amount) {
            $amount = $userDetails["banner_credits"];
        }
        $this->model->deductMemberBannerAdCredits($username, $amount);
    }
    public function deductMemberLoginAdCredits($username, $amount)
    {
        $userDetails = $this->getUserDetails($username);
        if ($userDetails["login_ad_credits"] < $amount) {
            $amount = $userDetails["login_ad_credits"];
        }
        $this->model->deductMemberLoginAdCredits($username, $amount);
    }
    public function increaseMemberTextAdCredits($username, $amount)
    {
        return $this->model->increaseMemberTextAdCredits($username, $amount);
    }
    public function increaseMemberBannerAdCredits($username, $amount)
    {
        return $this->model->increaseMemberBannerCredits($username, $amount);
    }
    public function increaseMemberLoginAdCredits($username, $amount)
    {
        return $this->model->increaseMemberLoginCredits($username, $amount);
    }
    public function changeUserEmailSubscription($username)
    {
        if (isset($_POST["subscription"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (empty($_POST["subscription"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($this->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if ($_POST["subscription"] != 3 && $_POST["subscription"] != 1) {
                return ["success" => false, "message" => "Invalid subscription value."];
            }
            $this->model->updateMemberAccount(["account_status" => $_POST["subscription"]], $username);
            if ($_POST["subscription"] == 1) {
                return ["success" => true, "message" => "Your account has been subscribed to get emails."];
            }
            return ["success" => true, "message" => "Your account has been unsubscribed. You will not get any email."];
        }
    }
    public function enableUserVacationMode($username, $userDetails)
    {
        if (isset($_POST["vacation_end"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (empty($_POST["vacation_end"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($this->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if ($userDetails["account_status"] == 4) {
                return ["success" => false, "message" => "Your account already in vacation mode."];
            }
            if ($userDetails["account_status"] != 1) {
                return ["success" => false, "message" => "Your account unsubscribed form our emails. You don't need vacation mode."];
            }
            $dateArray = explode("-", $_POST["vacation_end"]);
            if (checkdate($dateArray[1], $dateArray[2], $dateArray[0])) {
                $timeStamp = strtotime($_POST["vacation_end"] . " 00:00:00");
                if ($timeStamp < time()) {
                    return ["success" => false, "message" => "You can't set vacation mode in past"];
                }
                $endTime = strtotime($_POST["vacation_end"] . "00:00:00");
                $this->model->updateMemberAccount(["account_status" => 4, "vacation_end_time" => $endTime], $username);
                return ["success" => true, "message" => "Your account has set to vacation mode."];
            }
            return ["success" => false, "message" => "Invalid date."];
        }
    }
    public function endVacationMode($username)
    {
        if (isset($_GET["vacation"]) && isset($_GET["token"])) {
            if ($this->arrayCheck($_GET)) {
                return ["success" => false, "message" => "Array not allowed here"];
            }
            if (!empty($_GET["vacation"]) && !empty($_GET["token"]) && $_GET["token"] == $this->getUserCSRFToken()) {
                $this->model->updateMemberAccount(["account_status" => 1, "vacation_end_time" => time()], $username);
                return ["success" => true, "message" => "Your vacation mode has been disabled. Now you will get emails from our user and can earn credits."];
            }
        }
    }
    public function updateUserPassword($username, $userDetails)
    {
        if (isset($_POST["current_password"]) && isset($_POST["new_password"]) && isset($_POST["confirm_new_password"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (empty($_POST["current_password"]) || empty($_POST["new_password"]) || empty($_POST["confirm_new_password"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["csrf_token"] != $this->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (strlen($_POST["new_password"]) < $this->passwordMinLen) {
                return ["success" => false, "message" => "Password is too short. Please enter minimum " . $this->passwordMinLen . " characters."];
            }
            if ($_POST["new_password"] != $_POST["confirm_new_password"]) {
                return ["success" => false, "message" => "New password didn't match."];
            }
            if (!password_verify($this->passwordSalt . $_POST["current_password"], $userDetails["password"])) {
                return ["success" => false, "message" => "Current password didn't match."];
            }
            $this->model->updateMemberAccount(["password" => password_hash($this->passwordSalt . $_POST["new_password"], PASSWORD_BCRYPT)], $username);
            return ["success" => true, "message" => "Your password has been changed."];
        }
    }
    public function updateUserAccountInfo($username)
    {
        if (isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["phone"]) && isset($_POST["skype"]) && isset($_POST["telegram"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (empty($_POST["first_name"]) || empty($_POST["last_name"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "First and Last name required."];
            }
            if ($this->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if ($this->firstNameMaxLen < strlen($_POST["first_name"])) {
                return ["success" => false, "message" => "First name is too long."];
            }
            if ($this->lastNameMaxLen < strlen($_POST["last_name"])) {
                return ["success" => false, "message" => "Last name is too long."];
            }
            if (!empty($_POST["phone"]) && 50 < strlen($_POST["phone"])) {
                return ["success" => false, "message" => "Phone number is too long."];
            }
            if (!empty($_POST["phone"]) && !is_numeric($_POST["phone"])) {
                return ["success" => false, "message" => "Invalid phone number."];
            }
            if (!empty($_POST["skype"]) && 200 < strlen($_POST["skype"])) {
                return ["success" => false, "message" => "Skype is too long."];
            }
            if (!empty($_POST["telegram"]) && 200 < strlen($_POST["telegram"])) {
                return ["success" => false, "message" => "Telegram is too long."];
            }
            $this->model->updateMemberAccount(["first_name" => $_POST["first_name"], "last_name" => $_POST["last_name"], "phone" => $_POST["phone"], "skype" => $_POST["skype"], "telegram" => $_POST["telegram"]], $username);
            return ["success" => true, "message" => "Your account details has been updated."];
        }
    }
    public function updateUserEmail($username, $userDetails)
    {
        if (isset($_POST["email"]) && isset($_POST["current_password"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            if (empty($_POST["email"]) || empty($_POST["current_password"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "Please enter email and your current password."];
            }
            if ($this->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                return ["success" => false, "message" => "Invalid email. Please enter a valid email address."];
            }
            if (!password_verify($this->passwordSalt . $_POST["current_password"], $userDetails["password"])) {
                return ["success" => false, "message" => "Current password didn't match."];
            }
            if ($_POST["email"] != $userDetails["email"] && $this->userInfoByEmail($_POST["email"])) {
                return ["success" => false, "message" => "Email already assigned to another account. Please enter a different email."];
            }
            $this->model->updateMemberAccount(["email" => $_POST["email"]], $username);
            return ["success" => true, "message" => "Your email has been updated."];
        }
    }
    public function updateUserProfileImage($username, $userDetails)
    {
        if (isset($_FILES["image"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            if (empty($_FILES["image"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "Please select image."];
            }
            if ($this->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            $target_dir = "uploads/";
            $image = $_FILES['image']['name'];
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_size = $_FILES['image']['size'];

            $target_file = $target_dir . $username . '-' . basename($image);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_extensions = array("jpg", "jpeg", "png");
            $max_file_size = 2 * 1024 * 1024;
            $check = getimagesize($image_tmp);
            if ($check === false) {
                return ["success" => false, "message" => "File is not an image."];
            }
            if (!in_array($imageFileType, $allowed_extensions)) {
                return ["success" => false, "message" => "Only JPG, JPEG And PNG files are allowed."];
            }
            if ($image_size > $max_file_size) {
                return ["success" => false, "message" => "File is too large. Maximum file size is 2MB."];
            }
            if (move_uploaded_file($image_tmp, $target_file)) {
                echo "The file " . htmlspecialchars(basename($image)) . " has been uploaded.";
                $this->model->updateMemberAccount(["profile_image" => $target_file], $username);
                return ["success" => true, "message" => "Your Profile Image has been updated."];
            } else {
                echo "Sorry, there was an error uploading your file.";
                return ["success" => false, "message" => "Sorry, there was an error uploading your file."];
            }
        }
    }
    public function updateUserPaymentMethod($username)
    {
        $affiliateSettingsController = new AffiliateSettingsController();
        $affiliateSettings = $affiliateSettingsController->getSettings();
        if ($affiliateSettings["paypal"] == 1 && isset($_POST["paypal"]) || $affiliateSettings["btc_coinbase"] == 1 && isset($_POST["btc_coinbase"]) || $affiliateSettings["skrill"] == 1 && isset($_POST["skrill"]) || $affiliateSettings["transfer_wise"] == 1 && isset($_POST["transfer_wise"]) || $affiliateSettings["perfect_money"] == 1 && isset($_POST["perfect_money"]) || $affiliateSettings["eth_wallet"] == 1 && isset($_POST["eth_wallet"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if ($this->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if ($affiliateSettings["paypal"] == 1 && empty($_POST["paypal"]) && $affiliateSettings["btc_coinbase"] == 1 && empty($_POST["btc_coinbase"]) && $affiliateSettings["skrill"] == 1 && empty($_POST["skrill"]) || $affiliateSettings["transfer_wise"] == 1 && empty($_POST["transfer_wise"]) && $affiliateSettings["perfect_money"] == 1 && empty($_POST["perfect_money"]) && $affiliateSettings["eth_wallet"] == 1 && empty($_POST["eth_wallet"]) && empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "Please enter your payment method details."];
            }
            if ($affiliateSettings["paypal"] == 1 && isset($_POST["paypal"]) && !empty($_POST["paypal"]) && !filter_var($_POST["paypal"], FILTER_VALIDATE_EMAIL)) {
                return ["success" => false, "message" => "Invalid PayPal email."];
            }
            if ($affiliateSettings["btc_coinbase"] == 1 && isset($_POST["btc_coinbase"]) && !empty($_POST["btc_coinbase"]) && 100 < strlen($_POST["btc_coinbase"])) {
                return ["success" => false, "message" => "Invalid Coinbase wallet address."];
            }
            if ($affiliateSettings["skrill"] == 1 && isset($_POST["skrill"]) && !empty($_POST["skrill"]) && !filter_var($_POST["skrill"], FILTER_VALIDATE_EMAIL)) {
                return ["success" => false, "message" => "Invalid Skrill email."];
            }
            if ($affiliateSettings["transfer_wise"] == 1 && isset($_POST["transfer_wise"]) && !empty($_POST["transfer_wise"]) && !filter_var($_POST["transfer_wise"], FILTER_VALIDATE_EMAIL)) {
                return ["success" => false, "message" => "Invalid TransferWise email."];
            }
            if ($affiliateSettings["perfect_money"] == 1 && isset($_POST["perfect_money"]) && !empty($_POST["perfect_money"]) && !filter_var($_POST["perfect_money"], FILTER_VALIDATE_EMAIL)) {
                return ["success" => false, "message" => "Invalid PerfectMoney email."];
            }
            if ($affiliateSettings["eth_wallet"] == 1 && isset($_POST["eth_wallet"]) && !empty($_POST["eth_wallet"]) && 100 < strlen($_POST["eth_wallet"])) {
                return ["success" => false, "message" => "Invalid ETH wallet address."];
            }
            $paypal = $affiliateSettings["paypal"] == 1 && isset($_POST["paypal"]) ? $_POST["paypal"] : "";
            $btc_coinbase = $affiliateSettings["btc_coinbase"] == 1 && isset($_POST["btc_coinbase"]) ? $_POST["btc_coinbase"] : "";
            $skrill = $affiliateSettings["skrill"] == 1 && isset($_POST["skrill"]) ? $_POST["skrill"] : "";
            $transfer_wise = $affiliateSettings["transfer_wise"] == 1 && isset($_POST["transfer_wise"]) ? $_POST["transfer_wise"] : "";
            $perfect_money = $affiliateSettings["perfect_money"] == 1 && isset($_POST["perfect_money"]) ? $_POST["perfect_money"] : "";
            $eth_wallet = $affiliateSettings["eth_wallet"] == 1 && isset($_POST["eth_wallet"]) ? $_POST["eth_wallet"] : "";
            $this->model->updateMemberAccount(["paypal" => $paypal, "btc_coinbase" => $btc_coinbase, "skrill" => $skrill, "transfer_wise" => $transfer_wise, "perfect_money" => $perfect_money, "eth_wallet" => $eth_wallet], $username);
            return ["success" => true, "message" => "Payment method has been updated."];
        }
    }
    public function deductMemberBalance($username, $amount, $userDetails)
    {
        if ($userDetails["balance"] - $amount < 0) {
            $amount = $userDetails["balance"];
        }
        return $this->model->deductMbmberBalance($username, $amount);
    }
    public function memberReferrals($username)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalUserReferrals($username);
            $total_offset = ceil($total / 30);
            if ($_GET["page"] - 1 < 0) {
                $offset = 0;
            } else {
                if ($total_offset < $_GET["page"] - 1) {
                    $offset = 0;
                } else {
                    $offset = ($_GET["page"] - 1) * 30;
                }
            }
        }
        return $this->model->memberReferrals(30, $offset, $username);
    }
    public function memberReferrralsPagination($username)
    {
        return $this->pagination(30, $this->totalUserReferrals($username), "referrals.php");
    }
    public function searchMemberReferral($username)
    {
        if (isset($_GET["username"]) && !empty($username)) {
            return $this->model->searchMemberReferral($username, $_GET["username"]);
        }
    }
    public function promotionalBanners($username, $siteLink)
    {
        $allowedExtensions = ["jpg", "jpeg", "gif", "png"];
        $dirs = scandir("banners");
        if (!empty($dirs)) {
            foreach ($dirs as $file) {
                if (is_file("banners/" . $file)) {
                    $pathInfo = pathinfo("banners/" . $file);
                    if (!empty($pathInfo) && isset($pathInfo["extension"]) && in_array($pathInfo["extension"], $allowedExtensions)) {
                        echo "<center>";
                        echo "<img src=\"banners/" . $file . "\" /><br><br>";
                        echo "<textarea class=\"form-control\" rows=\"1\">" . $siteLink . "banners/" . $file . "</textarea><br>";
                        echo "<textarea class=\"form-control\" rows=\"2\">";
                        echo "<a href=\"" . $siteLink . "index.php?referrer=" . $username . "\" target=\"_blank\">";
                        echo "<img src=\"" . $siteLink . "banners/" . $file . "\" />";
                        echo "</a></textarea></center><br>";
                    }
                }
            }
        }
    }
    public function promotionalBannerList()
    {
        $allowedExtensions = ["jpg", "jpeg", "gif", "png"];
        $dirs = scandir("../banners");
        $banners = [];
        foreach ($dirs as $file) {
            if (is_file("../banners/" . $file)) {
                $pathInfo = pathinfo("../banners/" . $file);
                if (isset($pathInfo["extension"]) && in_array($pathInfo["extension"], $allowedExtensions)) {
                    array_push($banners, $file);
                }
            }
        }
        return $banners;
    }
    public function uploadBanner()
    {
        if (isset($_FILES["banner"]) && isset($_POST["admin_csrf_token"]) && !empty($_FILES["banner"]["name"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_POST["admin_csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            $pathInfo = pathinfo($_FILES["banner"]["name"]);
            $allowedExtensions = ["jpg", "jpeg", "gif", "png"];
            if (isset($pathInfo["extension"]) && in_array($pathInfo["extension"], $allowedExtensions)) {
                $newPath = "../banners/" . md5(uniqid("king")) . "." . $pathInfo["extension"];
                move_uploaded_file($_FILES["banner"]["tmp_name"], $newPath);
                return ["success" => true, "message" => "Banner has been added."];
            }
            return ["success" => false, "message" => "Invalid banner."];
        }
    }
    public function deleteBanner()
    {
        if (isset($_GET["banner"]) && isset($_GET["token"]) && !empty($_GET["banner"]) && !empty($_GET["token"])) {
            if ($this->arrayCheck($_GET)) {
                return ["success" => false, "message" => "Arrays not allowed."];
            }
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() == $_GET["token"]) {
                if (file_exists("../banners/" . $_GET["banner"])) {
                    unlink("../banners/" . $_GET["banner"]);
                    return ["success" => true, "message" => "Banner has been deleted."];
                }
                return ["success" => false, "message" => "Invalid banner."];
            }
        }
    }
    public function increaseReferralLinkClicks($username)
    {
        return $this->model->increaseReferralLinkClick($username);
    }
    public function referralLinkViews($userDetails)
    {
        $totalViews = $userDetails["referral_link_clicks"];
        if (1000 < $totalViews) {
            $totalViews = $totalViews / 1000;
            $totalViews = number_format($totalViews, 2);
            $totalViews = $totalViews . "K";
        }
        return $totalViews;
    }
    public function referralContestLeaderboard($startDate, $endDate)
    {
        return $this->model->referralContestLeaderboard($startDate, $endDate);
    }
    public function gravatar($email, $siteLink, $size = 150)
    {
        $userInfo = $this->model->getMemberDetailsByEmail($email);
        if(isset($userInfo["profile_image"]) && !empty($userInfo["profile_image"])){
            return $userInfo["profile_image"];
        }
        $default = $siteLink . "images/avatar.jpg";
        $avatar = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?d=" . urlencode($default) . "&s=" . $size;
        return $avatar;
    }
    public function deductMemberCredits($username, $amount)
    {
        return $this->model->deductMbmberCredits($username, $amount);
    }
    public function convertCreditToTextAdCredits($username, $userDetails, $conversationRate)
    {
        if (isset($_POST["text_ad_credit"]) && isset($_POST["credit_amount"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (empty($_POST["credit_amount"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "Please enter credit amount."];
            }
            if ($_POST["csrf_token"] != $this->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["credit_amount"]) || $_POST["credit_amount"] < 1) {
                return ["success" => false, "message" => "Invalid credit amount."];
            }
            if ($userDetails["credits"] < $_POST["credit_amount"]) {
                return ["success" => false, "message" => "You don't have " . $_POST["credit_amount"] . " credits. Currently you have " . $userDetails["credits"]];
            }
            $totalCredits = $conversationRate * intval($_POST["credit_amount"]);
            $this->increaseMemberTextAdCredits($username, $totalCredits);
            $this->deductMemberCredits($username, intval($_POST["credit_amount"]));
            return ["success" => true, "message" => intval($_POST["credit_amount"]) . " email credit(s) has been converted to " . $totalCredits . " text ad credits."];
        }
    }
    public function convertCreditToLoginAdCredits($username, $userDetails, $conversationRate)
    {
        if (isset($_POST["login_ad_credit"]) && isset($_POST["credit_amount"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (empty($_POST["credit_amount"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "Please enter credit amount."];
            }
            if ($_POST["csrf_token"] != $this->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["credit_amount"]) || $_POST["credit_amount"] < 1) {
                return ["success" => false, "message" => "Invalid credit amount."];
            }
            if ($userDetails["credits"] < intval($_POST["credit_amount"]) * $conversationRate) {
                return ["success" => false, "message" => "You don't have " . $_POST["credit_amount"] * $conversationRate . " credits. Currently you have " . $userDetails["credits"]];
            }
            $totalCredits = intval($_POST["credit_amount"]);
            $this->increaseMemberLoginAdCredits($username, $_POST["credit_amount"]);
            $this->deductMemberCredits($username, intval($_POST["credit_amount"]) * $conversationRate);
            return ["success" => true, "message" => intval($_POST["credit_amount"]) * $conversationRate . " email credit(s) has been converted to " . $totalCredits . " login ad credits."];
        }
    }
    public function convertCreditToBannerCredits($username, $userDetails, $conversationRate)
    {
        if (isset($_POST["banner_ad_credit"]) && isset($_POST["credit_amount"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (empty($_POST["credit_amount"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "Please enter credit amount."];
            }
            if ($_POST["csrf_token"] != $this->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["credit_amount"]) || $_POST["credit_amount"] < 1) {
                return ["success" => false, "message" => "Invalid credit amount."];
            }
            if ($userDetails["credits"] < $_POST["credit_amount"]) {
                return ["success" => false, "message" => "You don't have " . $_POST["credit_amount"] . " credits. Currently you have " . $userDetails["credits"]];
            }
            $totalCredits = $conversationRate * intval($_POST["credit_amount"]);
            $this->increaseMemberBannerAdCredits($username, $totalCredits);
            $this->deductMemberCredits($username, intval($_POST["credit_amount"]));
            return ["success" => true, "message" => intval($_POST["credit_amount"]) . " email credit(s) has been converted to " . $totalCredits . " banner ad credits."];
        }
    }
    public function memberAccountActivation()
    {
        if (isset($_GET["action"]) && $_GET["action"] == "activate" && isset($_GET["user"]) && isset($_GET["key"])) {
            if ($this->arrayCheck($_GET)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (!empty($_GET["user"]) && !empty($_GET["key"])) {
                $userDetails = $this->getUserDetails($_GET["user"]);
                if (empty($userDetails)) {
                    return ["success" => false, "message" => "Invalid activation link."];
                }
                if ($userDetails["account_status"] != 0) {
                    return ["success" => false, "message" => "You can't activate your account twice."];
                }
                if ($userDetails["account_activation_key"] != $_GET["key"]) {
                    return ["success" => false, "message" => "Invalid or expired activation link."];
                }
                $this->model->updateMemberAccount(["account_status" => 1, "account_activation_key" => md5(time())], $_GET["user"]);
                return ["success" => true, "message" => "Your account has been activated. You can login now."];
            }
        }
    }
    public function requestActivationMail()
    {
        if (isset($_POST["username"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (empty($_POST["username"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "Please enter your username."];
            }
            if ($_POST["csrf_token"] != $this->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid reqeust."];
            }
            $userDetails = $this->getUserDetails($_POST["username"]);
            if (empty($userDetails)) {
                return ["success" => false, "message" => "Couldn't find the user."];
            }
            if ($userDetails["account_status"] != 0) {
                return ["success" => false, "message" => "You can't activate your account twice."];
            }
            if ($userDetails["account_activation_request_time"] != 0 && time() - $userDetails["account_activation_request_time"] < 300) {
                return ["success" => false, "message" => "Please wait minimum 5 minutes before requesting another activation email."];
            }
            $siteSettingsController = new SiteSettingsController();
            $siteSettigsData = $siteSettingsController->getSettings();
            $accountActivationKey = $userDetails["account_activation_key"];
            $activationLink = $siteSettigsData["installation_url"] . "login.php?action=activate&user=" . $_POST["username"] . "&key=" . $accountActivationKey;
            $activationMassege = "Dear " . $userDetails["first_name"];
            $activationMassege .= "Please click the button below to activate your account.<br>";
            $activationMassege .= "If the button doesn't work, copy and paste this link into your browser: " . $activationLink . "<br>";
            $this->model->updateMemberAccount(["account_activation_request_time" => time()], $_POST["username"]);
            SingleEmailSystem::sendEmail("no-reply@" . parse_url($siteSettigsData["installation_url"])["host"], $siteSettigsData["site_title"], $userDetails["email"], $userDetails["first_name"] . " " . $userDetails["last_name"], "Activate your account", SystemEmailTemplate::emailTemplate($siteSettigsData["logo_link"], $siteSettigsData["installation_url"], "Account Activation", $activationMassege, $activationLink, "Activate Account"));
            return ["success" => true, "message" => "We have sent you an activation link. Please check your inbox/spam."];
        }
    }
    public function requestPasswordResetMail()
    {
        if (isset($_POST["username"]) && isset($_POST["csrf_token"])) {
            if (empty($_POST["username"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "Please enter your username."];
            }
            if ($_POST["csrf_token"] != $this->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid reqeust."];
            }
            $userDetails = $this->getUserDetails($_POST["username"]);
            if (empty($userDetails)) {
                return ["success" => false, "message" => "Couldn't find the user."];
            }
            if ($userDetails["password_reset_request_time"] != 0 && time() - $userDetails["password_reset_request_time"] < 300) {
                return ["success" => false, "message" => "Please wait minimum 5 minutes before requesting another password reset email."];
            }
            $siteSettingsController = new SiteSettingsController();
            $siteSettigsData = $siteSettingsController->getSettings();
            $passwordResetKey = $userDetails["password_reset"];
            $passwordResetLink = $siteSettigsData["installation_url"] . "forget.php?action=reset&user=" . $_POST["username"] . "&key=" . $passwordResetKey;
            $message = "Dear " . $userDetails["first_name"];
            $message .= "Please click the button below to reset your account password.<br>";
            $message .= "If the button doesn't work, copy and paste this link into your browser: " . $passwordResetLink . "<br>";
            $this->model->updateMemberAccount(["password_reset_request_time" => time()], $_POST["username"]);
            SingleEmailSystem::sendEmail("no-reply@" . parse_url($siteSettigsData["installation_url"])["host"], $siteSettigsData["site_title"], $userDetails["email"], $userDetails["first_name"] . " " . $userDetails["last_name"], "Password reset request", SystemEmailTemplate::emailTemplate($siteSettigsData["logo_link"], $siteSettigsData["installation_url"], "Password Reset", $message, $passwordResetLink, "Reset Password"));
            return ["success" => true, "message" => "We have sent you an email with password reset instruction. Please check your inbox/spam."];
        }
    }
    public function resetMemberPassword($username, $key)
    {
        if (isset($_POST["password"]) && isset($_POST["confirm_password"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (empty($_POST["password"]) || empty($_POST["confirm_password"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["csrf_token"] != $this->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if ($_POST["password"] != $_POST["confirm_password"]) {
                return ["success" => false, "message" => "New password didn't match."];
            }
            if (strlen($_POST["password"]) < $this->passwordMinLen) {
                return ["success" => false, "message" => "Password is too short."];
            }
            $userDetails = $this->getUserDetails($username);
            if (empty($userDetails)) {
                return ["success" => false, "message" => "Invalid password reset link."];
            }
            if ($key != $userDetails["password_reset"]) {
                return ["success" => false, "message" => "Invalid password reset link."];
            }
            $this->model->updateMemberAccount(["password" => password_hash($this->passwordSalt . $_POST["password"], PASSWORD_BCRYPT), "password_reset_request_time" => time(), "password_reset" => md5(uniqid("ntkS"))], $username);
            return ["success" => true, "message" => "Your account password has been changed."];
        }
    }
    public function verifyLoggedIn($arg)
    {
        if (isset($_SESSION["user_token"]) && isset($_SESSION["logged_username"])) {
            $tokenController = new MembersTokenController();
            $tokenDetails = $tokenController->tokenDetails($_SESSION["user_token"]);
            if ($_SESSION["logged_username"] != $tokenDetails["username"]) {
                if ($arg != "login") {
                    header("Location: login.php");
                    exit;
                }
            } else {
                $userDetails = $this->getUserDetails($_SESSION["logged_username"]);
                if (empty($userDetails)) {
                    if ($arg != "login") {
                        header("Location: login.php");
                        exit;
                    }
                } else {
                    if ($userDetails["account_status"] == 0 || $userDetails["account_status"] == 2) {
                        if ($arg != "login") {
                            header("Location: login.php");
                            exit;
                        }
                    } else {
                        if ($arg == "login") {
                            header("Location: dashboard.php");
                            exit;
                        }
                    }
                }
            }
        } else {
            if ($arg != "login") {
                header("Location: login.php");
                exit;
            }
        }
    }
    public function login()
    {
        if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "Please enter your username and password."];
            }
            if (preg_match("/\\s/", $_POST["username"])) {
                return ["success" => false, "message" => "Space not allowed in username."];
            }
            if ($_POST["csrf_token"] != $this->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            $userDetails = $this->getUserDetails($_POST["username"]);
            if (empty($userDetails)) {
                return ["success" => false, "message" => "Invalid username or password."];
            }
            if (!password_verify($this->passwordSalt . $_POST["password"], $userDetails["password"])) {
                return ["success" => false, "message" => "Invalid username or password."];
            }
            if ($userDetails["account_status"] == 0) {
                return ["success" => false, "message" => "Your account is inactive. You need to activate your account."];
            }
            if ($userDetails["account_status"] == 2) {
                return ["success" => false, "message" => "Your account has been banned."];
            }
            $loginAdsController = new LoginAdsController();
            // $loginAdController = new LoginSpotlightAdsController();
            $loginAdClickController = new LoginSpotlightAdClickController();
            $loginTokenController = new MembersTokenController();
            $specialOfferPageController = new SpecialOfferPagesController();
            $todayLoginAd = $loginAdsController->getLoginAd();
            $todayLoginClickeAdCount = $loginAdClickController->getTodayAdCount($_POST["username"], $todayLoginAd["id"]);
            $loginOfferPage = $specialOfferPageController->getLoginOfferPage();
            $loginToken = md5(uniqid("ntkS++"));
            $_SESSION["logged_username"] = $_POST["username"];
            $_SESSION["user_token"] = $loginToken;
            $_SESSION["user_login_csrf"] = md5(uniqid("sadness"));
            $loginTokenController->addToken(["username" => $_POST["username"], "token" => $loginToken, "timestamp" => time()]);
            $this->model->updateMemberAccount(["last_login_timestamp" => time()], $_POST["username"]);
            if (!empty($todayLoginAd)) {
            // if (!empty($todayLoginAd) && $todayLoginClickeAdCount == 0) {
                header("Location: email-credits.php?type=loginads&id=" . $todayLoginAd["id"]);
            } else {
                if (!empty($loginOfferPage)) {
                    header("Location: special-offer.php?id=" . $loginOfferPage["id"]);
                    exit;
                }
                header("Location: dashboard.php");
                exit;
            }
        }
    }
    public function logout($username)
    {
        if (isset($_GET["logout"]) && !empty($_GET["logout"]) && $_GET["logout"] == $_SESSION["user_login_csrf"]) {
            $tokenController = new MembersTokenController();
            $tokenController->deleteUserAllToken($username);
            unset($_SESSION["logged_username"]);
            unset($_SESSION["user_token"]);
            unset($_SESSION["user_login_csrf"]);
            header("Location: login.php");
            exit;
        }
    }
    public function memberSupportMail($userDetails, $siteSettigsData)
    {
        if (isset($_POST["support_subject"]) && isset($_POST["support_message"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            if (empty($_POST["support_subject"]) || empty($_POST["support_message"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if (100 < strlen($_POST["support_subject"])) {
                return ["success" => false, "message" => "Subject is too long."];
            }
            $message = "Username : " . $userDetails["username"] . "<br>";
            $message .= "Email : " . $userDetails["email"] . "<br>";
            $message .= "Message : <br>" . $_POST["support_message"] . "<br>";
            SingleEmailSystem::sendEmail($userDetails["email"], $userDetails["first_name"], $siteSettigsData["admin_email"], $siteSettigsData["site_title"] . " Admin", "Support Request : " . $_POST["support_subject"], $message);
            return ["success" => true, "message" => "Thanks for your email. The administration will contact you shortly."];
        }
    }
    public function userIPInfo($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $this->model->userIPInfo($ip);
        }
    }
    public function topReferrersThisMonth()
    {
        return $this->model->topReferrersThisMonth();
    }
    public function updateAutoEmail($userInfo)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (1 < $userInfo["membership"] && $userInfo["membership_end_time"] == "Lifetime" || time() < $userInfo["membership_end_time"]) {
                if (isset($_POST["auto_email_subject"]) && isset($_POST["auto_email_body"]) && isset($_POST["auto_email_website"]) && isset($_POST["auto_email_status"]) && isset($_POST["csrf_token"])) {
                    if ($this->arrayCheck($_POST)) {
                        return ["success" => false, "message" => "Array not allowed here."];
                    }
                    $membersController = new MembersController();
                    if (empty($_POST["auto_email_subject"]) || empty($_POST["auto_email_body"]) || empty($_POST["auto_email_website"]) || empty($_POST["auto_email_status"]) || empty($_POST["csrf_token"])) {
                        return ["success" => false, "message" => "All fields are required."];
                    }
                    if ($membersController->getUserCSRFToken() != $_POST["csrf_token"]) {
                        return ["success" => false, "message" => "Invalid request."];
                    }
                    if (!filter_var($_POST["auto_email_website"], FILTER_SANITIZE_URL)) {
                        return ["success" => false, "message" => "Invalid website link."];
                    }
                    if (50 < strlen($_POST["auto_email_subject"])) {
                        return ["success" => false, "message" => "Email subject is too long."];
                    }
                    if (255 < strlen($_POST["auto_email_website"])) {
                        return ["success" => false, "message" => "Website link is too long."];
                    }
                    if (!is_numeric($_POST["auto_email_status"])) {
                        return ["success" => false, "message" => "Invalid status."];
                    }
                    if ($_POST["auto_email_status"] != 1 && $_POST["auto_email_status"] != 2) {
                        return ["success" => false, "message" => "Invalid status."];
                    }
                    $this->model->updateMemberDetails(["auto_email_subject" => base64_encode($_POST["auto_email_subject"]), "auto_email_body" => base64_encode($_POST["auto_email_body"]), "auto_email_website" => $_POST["auto_email_website"], "auto_email_status" => $_POST["auto_email_status"]], $userInfo["username"]);
                    return ["success" => true, "message" => "Automatic email has been updated."];
                }
            } else {
                return ["success" => false, "message" => "Please upgrade or renew your membership."];
            }
        }
    }
    public function updateMemberProfileData($username, $data)
    {
        return $this->model->updateMemberDetails($data, $username);
    }
    public function addEmailCredits($username, $amount)
    {
        return $this->model->addEmailCredits($username, $amount);
    }
    public function membershipChange($username, $bonus_credits, $bonus_text_ad, $bonus_banner_ad, $membership_id, $membership_end)
    {
        return $this->model->membershipChange($username, $bonus_credits, $bonus_text_ad, $bonus_banner_ad, $membership_id, $membership_end);
    }
    public function getAllMembers()
    {
        return $this->model->getAllMembers();
    }
    public function getAutoEmails()
    {
        return $this->model->getAutoEmails();
    }
    public function getMembersByMembership($membershipId)
    {
        return $this->model->getMembersByMembership($membershipId);
    }
    public function getMembershipEndList()
    {
        return $this->model->membershipEndList();
    }
    public function vacationEndMemberList()
    {
        return $this->model->vacationEndList();
    }
    public function increaseEmailClick($username)
    {
        return $this->model->increaseEmailClick($username);
    }
    public function refHomeLink()
    {
        $siteController = new SiteSettingsController();
        $siteSettings = $siteController->getSettings();
        if (isset($_GET["referrer"]) && !empty($_GET["referrer"])) {
            return $siteSettings["installation_url"] . "index.php?referrer=" . $_GET["referrer"];
        }
        return $siteSettings["installation_url"];
    }
    public function refRegLink()
    {
        $siteController = new SiteSettingsController();
        $siteSettings = $siteController->getSettings();
        if (isset($_GET["referrer"]) && !empty($_GET["referrer"])) {
            return $siteSettings["installation_url"] . "register.php?referrer=" . $_GET["referrer"];
        }
        return $siteSettings["installation_url"] . "register.php";
    }
    public function deleteAwaitingMember()
    {
        if (isset($_GET["delete"]) && isset($_GET["token"]) && (!empty($_GET["delete"]) || !empty($_GET["token"]) && is_numeric($_GET["delete"]))) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() == $_GET["token"]) {
                $membersController = new MembersController();
                $memberDetails = $membersController->getUserDetails($_GET["delete"]);
                if (!empty($memberDetails)) {
                    if ($memberDetails["account_status"] == 0) {
                        $this->model->deleteMember($_GET["delete"]);
                        return ["success" => true, "message" => "Member has been deleted."];
                    }
                    return ["success" => false, "message" => "Sorry, you can only delete awaiting activation member."];
                }
                return ["success" => false, "message" => "Couldn't find the user."];
            }
            return ["success" => false, "message" => "Invalid request."];
        }
    }
    public function unsubscribe()
    {
        if (isset($_GET["unsubscribe"]) && isset($_GET["username"]) && !empty($_GET["username"]) && !empty($_GET["unsubscribe"])) {
            $membersInfo = $this->getUserDetails($_GET["username"]);
            if (empty($membersInfo)) {
                $flag = ["success" => false, "message" => "Invalid username"];
            } else {
                if ($membersInfo["account_status"] != 1) {
                    $flag = ["success" => false, "message" => "You have already unsubscribed"];
                } else {
                    if ($membersInfo["account_activation_key"] == $_GET["unsubscribe"]) {
                        $this->model->updateMemberAccount(["account_status" => 3, "account_activation_key" => md5("NTKS" . time())], $_GET["username"]);
                        $flag = ["success" => true, "message" => "You have successfully unsubscribed from our mailing list"];
                    }
                }
            }
        }
    }
}

?>