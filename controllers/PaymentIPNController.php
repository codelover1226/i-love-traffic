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
if (file_exists("nsms_load_classes.php")) {
    require_once "nsms_load_classes.php";
    $soloEmailSystem = true;
} else {
    if (file_exists("../nsms_load_classes.php")) {
        require_once "../nsms_load_classes.php";
        $soloEmailSystem = true;
    } else {
        if (file_exists("../../nsms_load_classes.php")) {
            require_once "../../nsms_load_classes.php";
            $soloEmailSystem = true;
        } else {
            if (file_exists("../../../nsms_load_classes.php")) {
                require_once "../../../nsms_load_classes.php";
                $soloEmailSystem = true;
            }
        }
    }
}
class PaymentIPNController extends Controller
{
    private function membership($buyerInfo, $membershipDetails, $trxID)
    {
        $expireDate = "";
        if ($membershipDetails["subscription_type"] == 2) {
            $expireDate = strtotime(strval(date("d-M-Y")) . " + 1 month");
        } else {
            if ($membershipDetails["subscription_type"] == 3) {
                $expireDate = strtotime(strval(date("d-M-Y")) . " + 1 year");
            } else {
                if ($membershipDetails["subscription_type"] == 4) {
                    $expireDate = "lifetime";
                }
            }
        }
        if (!empty($buyerInfo["membership_end_time"]) && !empty($expireDate) && is_numeric($expireDate) && $buyerInfo["membership"] == $membershipDetails["id"] && is_numeric($buyerInfo["membership_end_time"]) && time() < $buyerInfo["membership_end_time"]) {
            if ($membershipDetails["subscription_type"] == 2) {
                $expireDate = strtotime(strval(date("d-M-Y", $buyerInfo["membership_end_time"])) . " + 1 month");
            } else {
                if ($membershipDetails["subscription_type"] == 3) {
                    $expireDate = strtotime(strval(date("d-M-Y", $buyerInfo["membership_end_time"])) . " + 1 year");
                }
            }
        }
        if (!empty($expireDate)) {
            $membersController = new MembersController();
            $membersController->membershipChange($buyerInfo["username"], $membershipDetails["bonus_email_credits"], $membershipDetails["bonus_text_ad_credits"], $membershipDetails["bonus_banner_credits"], $membershipDetails["id"], $expireDate);
            $siteSettingsController = new SiteSettingsController();
            $siteSettings = $siteSettingsController->getSettings();
            $adminMessage = "Dear Admin, <br>" . $buyerInfo["username"] . " has bought " . $membershipDetails["membership_title"];
            $adminMessage .= " <br>";
            $adminMessage .= "Total Paid : \$" . $membershipDetails["price"] . "<br>";
            $adminMessage .= "Paid Date : " . date("d M, Y") . "<br>";
            $adminMessage .= "Transaction ID : " . $trxID . "<br>";
            SingleEmailSystem::sendEmail($siteSettings["admin_email"], $siteSettings["site_title"], $siteSettings["admin_email"], "Admin", "New Membership Order Notification", $adminMessage);
            $buyerMessage = "Dear " . $buyerInfo["username"] . "<br>";
            $buyerMessage .= "Thanks for buying our membership. We have upgraded your membership.";
            $buyerMessage .= "<br>";
            $buyerMessage .= "Membership : " . $membershipDetails["membership_title"] . "<br>";
            $buyerMessage .= "Total Cost : \$" . $membershipDetails["price"] . "<br>";
            $buyerMessage .= "Paid Date : " . date("d M, Y") . "<br>";
            $buyerMessage .= "Transaction ID : " . $trxID . "<br>";
            SingleEmailSystem::sendEmail($siteSettings["admin_email"], $siteSettings["site_title"], $buyerInfo["email"], $buyerInfo["first_name"] . " " . $buyerInfo["last_name"], "New Membership Order Notification", $buyerMessage);
        }
    }
    private function credits($buyerInfo, $credits, $paymentAmount, $trxID)
    {
        $membersController = new MembersController();
        $membersController->addEmailCredits($buyerInfo["username"], $credits);
        $siteSettingsController = new SiteSettingsController();
        $siteSettings = $siteSettingsController->getSettings();
        $adminMessage = "Dear Admin, <br>" . $buyerInfo["username"] . " has bought " . $credits . " email credits";
        $adminMessage .= " <br>";
        $adminMessage .= "Total Paid : \$" . $paymentAmount . "<br>";
        $adminMessage .= "Paid Date : " . date("d M, Y") . "<br>";
        $adminMessage .= "Transaction ID : " . $trxID . "<br>";
        SingleEmailSystem::sendEmail($siteSettings["admin_email"], $siteSettings["site_title"], $siteSettings["admin_email"], "Admin", "Email Credits Order Notification", $adminMessage);
        $buyerMessage = "Dear " . $buyerInfo["username"] . "<br>";
        $buyerMessage .= "Thanks for buying email credits.";
        $buyerMessage .= "<br>";
        $buyerMessage .= "Total Credits : " . $credits . "<br>";
        $buyerMessage .= "Total Cost : \$" . $paymentAmount . "<br>";
        $buyerMessage .= "Paid Date : " . date("d M, Y") . "<br>";
        $buyerMessage .= "Transaction ID : " . $trxID . "<br>";
        SingleEmailSystem::sendEmail($siteSettings["admin_email"], $siteSettings["site_title"], $buyerInfo["email"], $buyerInfo["first_name"] . " " . $buyerInfo["last_name"], "Email Credits Order Notification", $buyerMessage);
    }
    private function combo($buyerInfo, $comboDetails, $trxID)
    {
        $siteSettingsController = new SiteSettingsController();
        $siteSettings = $siteSettingsController->getSettings();
        $adminMessage = "Dear Admin, <br>" . $buyerInfo["username"] . " has bought " . $comboDetails["product_title"];
        $adminMessage .= " <br>You need to add the combo offer's credits/membership etc. manually.<br>";
        $adminMessage .= "Offer Description : " . $comboDetails["product_description"] . "<br>";
        $adminMessage .= "Total Paid : \$" . $comboDetails["product_price"] . "<br>";
        $adminMessage .= "Paid Date : " . date("d M, Y") . "<br>";
        $adminMessage .= "Buyer Username  : " . $buyerInfo["username"] . "<br>";
        $adminMessage .= "Transaction ID : " . $trxID . "<br>";
        SingleEmailSystem::sendEmail($siteSettings["admin_email"], $siteSettings["site_title"], $siteSettings["admin_email"], "Admin", "New Combo Offer Order Notification", $adminMessage);
        $buyerMessage = "Dear " . $buyerInfo["username"] . "<br>";
        $buyerMessage .= "Thanks for buying our combo offer. An admin will add your combo offer's credits/memberships etc. in your account soon.";
        $buyerMessage .= "<br>";
        $buyerMessage .= "Offer Title : " . $comboDetails["product_title"] . "<br>";
        $buyerMessage .= "Total Cost : \$" . $comboDetails["product_price"] . "<br>";
        $buyerMessage .= "Paid Date : " . date("d M, Y") . "<br>";
        $buyerMessage .= "Transaction ID : " . $trxID . "<br>";
        SingleEmailSystem::sendEmail($siteSettings["admin_email"], $siteSettings["site_title"], $buyerInfo["email"], $buyerInfo["first_name"] . " " . $buyerInfo["last_name"], "New Combo Offer Order Notification", $buyerMessage);
    }
    private function loginads($buyerInfo, $date, $paymentAmount, $trxID, $websiteLink)
    {
        $loginAdsController = new LoginSpotlightAdsController();
        $loginAdSettingsController = new LoginSpotlightAdSettingsController();
        $loginAdsSettings = $loginAdSettingsController->getSettings();
        $loginAdsController->addNewAd(["username" => $buyerInfo["username"], "website_link" => $websiteLink, "status" => 1, "ad_timestamp" => strtotime($date . " 00:00:00"), "credit_key" => md5(uniqid("NTKS_")), "user_credits" => $loginAdsSettings["user_credits"], "total_views" => 0]);
        $siteSettingsController = new SiteSettingsController();
        $siteSettings = $siteSettingsController->getSettings();
        $adminMessage = "Dear Admin, <br>" . $buyerInfo["username"] . " has bought login ads for " . $date;
        $adminMessage .= "Total Paid : \$" . $paymentAmount . "<br>";
        $adminMessage .= "Paid Date : " . date("d M, Y") . "<br>";
        $adminMessage .= "Transaction ID : " . $trxID . "<br>";
        SingleEmailSystem::sendEmail($siteSettings["admin_email"], $siteSettings["site_title"], $siteSettings["admin_email"], "Admin", "New Login Ads Order Notification", $adminMessage);
        $buyerMessage = "Dear " . $buyerInfo["username"] . "<br>";
        $buyerMessage .= "Thanks for buying login ads. Your website has been added to our login ad list and will be shown on " . $date;
        $buyerMessage .= "<br>";
        $buyerMessage .= "Total Cost : \$" . $paymentAmount . "<br>";
        $buyerMessage .= "Paid Date : " . date("d M, Y") . "<br>";
        $buyerMessage .= "Transaction ID : " . $trxID . "<br>";
        $buyerMessage .= "Login Ad Date : " . $date . "<br>";
        SingleEmailSystem::sendEmail($siteSettings["admin_email"], $siteSettings["site_title"], $buyerInfo["email"], $buyerInfo["first_name"] . " " . $buyerInfo["last_name"], "New Login Ads Order Notification", $buyerMessage);
    }
    public function processOrder($username, $productType, $productID, $amount, $paymentMethod, $trxID, $websiteLink = NULL)
    {
        $productsDetails = "";
        $membersController = new MembersController();
        $buyerInfo = $membersController->getUserDetails($username);
        if ($productType == "membership") {
            $membershipController = new MembershipsController();
            $productsDetails = $membershipController->getMembershipDetails($productID);
            if (empty($productsDetails)) {
                return ["success" => false, "message" => "Invalid product"];
            }
            if ($productsDetails["price"] != $amount) {
                return ["success" => false, "message" => "Invalid amount"];
            }
            if ($productsDetails["status"] != 1 || $productsDetails["subscription_type"] == 1) {
                return ["success" => false, "message" => "Invalid membership"];
            }
            if ($paymentMethod == "account") {
                if ($buyerInfo["balance"] < $amount) {
                    return ["success" => false, "message" => "You dont' have enough fund."];
                }
                $membersController->deductMemberBalance($username, $amount, $buyerInfo);
            }
            $affiliateCommission = $this->payCommission($buyerInfo, $amount);
            $this->addOrderHistory(["product_id" => $productID, "product_title" => $productsDetails["membership_title"] . " - Membership", "product_price" => $productsDetails["price"], "affiliate_username" => $buyerInfo["referrer"], "affiliate_commission" => $affiliateCommission, "payment_method" => $paymentMethod, "transaction_id" => $trxID, "buyer_username" => $username, "payment_status" => 1, "order_timestamp" => time()]);
            $this->membership($buyerInfo, $productsDetails, $trxID);
            $this->affiliateEmailNotification($buyerInfo, $amount, "Bought Membership", $productsDetails["membership_title"]);
            return ["success" => true, "message" => "Membership has been activated."];
        }
        if ($productType == "credits") {
            $emailCreditsController = new EmailCreditsPackagesController();
            $productsDetails = $emailCreditsController->getPackageDetails($productID);
            if (empty($productsDetails)) {
                return ["success" => false, "message" => "Invalid product."];
            }
            if ($productsDetails["price"] != $amount) {
                return ["success" => false, "message" => "Invalid product price."];
            }
            if ($paymentMethod == "account") {
                if ($buyerInfo["balance"] < $amount) {
                    return ["success" => false, "message" => "You dont' have enough fund."];
                }
                $membersController->deductMemberBalance($username, $amount, $buyerInfo);
            }
            $affiliateCommission = $this->payCommission($buyerInfo, $amount);
            $this->addOrderHistory(["product_id" => $productID, "product_title" => $productsDetails["credits"] . " Email credits", "product_price" => $productsDetails["price"], "affiliate_username" => $buyerInfo["referrer"], "affiliate_commission" => $affiliateCommission, "payment_method" => $paymentMethod, "transaction_id" => $trxID, "buyer_username" => $username, "payment_status" => 1, "order_timestamp" => time()]);
            $this->credits($buyerInfo, $productsDetails["credits"], $amount, $trxID);
            $this->affiliateEmailNotification($buyerInfo, $amount, "Bought Email Credits", $productsDetails["credits"]);
            return ["success" => true, "message" => "Email credits has been added to your account."];
        }
        if ($productType == "combo") {
            $productsController = new ProductsController();
            $productsDetails = $productsController->getProductDetails($productID);
            if (empty($productsDetails)) {
                return ["success" => false, "message" => "Invalid product."];
            }
            if ($productsDetails["product_price"] != $amount) {
                return ["success" => false, "message" => "Invalid product price."];
            }
            if ($productsDetails["status"] != 1) {
                return ["success" => false, "message" => "Invalid product."];
            }
            if ($paymentMethod == "account") {
                if ($buyerInfo["balance"] < $amount) {
                    return ["success" => false, "message" => "You dont' have enough fund."];
                }
                $membersController->deductMemberBalance($username, $amount, $buyerInfo);
            }
            $affiliateCommission = $this->payCommission($buyerInfo, $amount);
            $this->addOrderHistory(["product_id" => $productID, "product_title" => $productsDetails["product_title"], "product_price" => $productsDetails["product_price"], "affiliate_username" => $buyerInfo["referrer"], "affiliate_commission" => $affiliateCommission, "payment_method" => $paymentMethod, "transaction_id" => $trxID, "buyer_username" => $username, "payment_status" => 1, "order_timestamp" => time()]);
            $this->combo($buyerInfo, $productsDetails, $trxID);
            $this->affiliateEmailNotification($buyerInfo, $amount, "Bought Combo Offer", $productsDetails["product_title"]);
            return ["success" => true, "message" => "An admin will add your combo offer soon."];
        }
        if ($productType == "loginads" && $websiteLink != NULL) {
            $loginAdSettingsController = new LoginSpotlightAdSettingsController();
            $settingsData = $loginAdSettingsController->getSettings();
            if ($settingsData["ad_price"] != $amount) {
                return ["success" => false, "message" => "Invalid price."];
            }
            $loginAds = new LoginSpotlightAdsController();
            $availableDates = $loginAds->availableDates();
            if (!in_array($productID, $availableDates)) {
                return ["success" => false, "message" => "This date is not available."];
            }
            if ($paymentMethod == "account") {
                if ($buyerInfo["balance"] < $amount) {
                    return ["success" => false, "message" => "You dont' have enough fund."];
                }
                $membersController->deductMemberBalance($username, $amount, $buyerInfo);
            }
            $affiliateCommission = $this->payCommission($buyerInfo, $amount);
            $this->addOrderHistory(["product_id" => 0, "product_title" => "Login Ads - " . $productID, "product_price" => $settingsData["ad_price"], "affiliate_username" => $buyerInfo["referrer"], "affiliate_commission" => $affiliateCommission, "payment_method" => $paymentMethod, "transaction_id" => $trxID, "buyer_username" => $username, "payment_status" => 1, "order_timestamp" => time()]);
            $this->loginads($buyerInfo, $productID, $amount, $trxID, $websiteLink);
            $this->affiliateEmailNotification($buyerInfo, $amount, "Bought Product", "Login Ad");
            return ["success" => true, "message" => "Your website has been added to login spotlight."];
        }
        if ($productType == "solo-email" && $productID == 1 && isset($soloEmailSystem)) {
            $soloEmailSettingsController = new SoloEmailSettings();
            $soloEmailSettings = $soloEmailSettingsController->getSettings();
            if ($soloEmailSettings["price_per_mail"] != $amount) {
                return ["success" => false, "message" => "Invalid price."];
            }
            $affiliateCommission = $this->payCommission($buyerInfo, $amount);
            $this->addOrderHistory(["product_id" => 1, "product_title" => "Solo Email", "product_price" => $soloEmailSettings["price_per_mail"], "affiliate_username" => $buyerInfo["referrer"], "affiliate_commission" => $affiliateCommission, "payment_method" => $paymentMethod, "transaction_id" => $trxID, "buyer_username" => $username, "payment_status" => 1, "order_timestamp" => time()]);
            $this->soloEmail($buyerInfo, $amount, $trxID);
            $this->affiliateEmailNotification($buyerInfo, $amount, "Bought Product", "Solo Email");
            return ["success" => true, "message" => "Solo email has been added to your account."];
        }
    }
    private function soloEmail($buyerInfo, $paymentAmount, $trxID)
    {
        if (isset($soloEmailSystem)) {
            $soloEmailCreditController = new SoloEmailCredits();
            $soloEmailCreditController->insertCredit(["username" => $buyerInfo["username"], "timestamp" => time()]);
            $siteSettingsController = new SiteSettingsController();
            $siteSettings = $siteSettingsController->getSettings();
            $adminMessage = "Dear Admin, <br>" . $buyerInfo["username"] . " has bought solo email";
            $adminMessage .= "Total Paid : \$" . $paymentAmount . "<br>";
            $adminMessage .= "Paid Date : " . date("d M, Y") . "<br>";
            $adminMessage .= "Transaction ID : " . $trxID . "<br>";
            SingleEmailSystem::sendEmail($siteSettings["admin_email"], $siteSettings["site_title"], $siteSettings["admin_email"], "Admin", "New Solo Email Order Notification", $adminMessage);
            $buyerMessage = "Dear " . $buyerInfo["username"] . "<br>";
            $buyerMessage .= "Thanks for buying solo email. Solo email has been added to your account.";
            $buyerMessage .= "<br>";
            $buyerMessage .= "Total Cost : \$" . $paymentAmount . "<br>";
            $buyerMessage .= "Paid Date : " . date("d M, Y") . "<br>";
            $buyerMessage .= "Transaction ID : " . $trxID . "<br>";
            SingleEmailSystem::sendEmail($siteSettings["admin_email"], $siteSettings["site_title"], $buyerInfo["email"], $buyerInfo["first_name"] . " " . $buyerInfo["last_name"], "New Solo Email Order Notification", $buyerMessage);
        }
    }
    private function affiliateEmailNotification($buyerInfo, $amount, $product_type, $product_title)
    {
        if (!empty($buyerInfo["referrer"])) {
            $membersController = new MembersController();
            $affiliateDetails = $membersController->getUserDetails($buyerInfo["referrer"]);
            if (!empty($affiliateDetails)) {
                $commission = $affiliateDetails["sales_commission"] * $amount / 100;
                $siteSettingsController = new SiteSettingsController();
                $siteSettings = $siteSettingsController->getSettings();
                $affiliateNotificationMessage = "Dear " . $affiliateDetails["first_name"] . " " . $affiliateDetails["last_name"] . "<br>";
                $affiliateNotificationMessage .= "Congratulations ! You have earned a commission. <br>";
                $affiliateNotificationMessage .= "Downline Member's Username : " . $buyerInfo["username"] . "<br>";
                $affiliateNotificationMessage .= $product_type . " : " . $product_title . "<br>";
                $affiliateNotificationMessage .= "Your Commission Amount : \$" . $commission . "<br>";
                $affiliateNotificationMessage .= "<br><br>Keep it up and best of luck for your next commission.<br>";
                SingleEmailSystem::sendEmail($siteSettings["admin_email"], $siteSettings["site_title"], $affiliateDetails["email"], $affiliateDetails["first_name"] . " " . $affiliateDetails["last_name"], $siteSettings["site_title"] . ":: Affilite commission  for your downline member - " . $buyerInfo["username"], $affiliateNotificationMessage);
            }
        }
    }
    private function payCommission($buyerInfo, $amount)
    {
        $membersController = new MembersController();
        if (!empty($buyerInfo["referrer"])) {
            $affiliateInfo = $membersController->getUserDetails($buyerInfo["referrer"]);
            if (!empty($affiliateInfo)) {
                $commission = $affiliateInfo["sales_commission"] * $amount / 100;
                $membersController->addBalance($buyerInfo["referrer"], $commission);
                return $commission;
            }
            return 0;
        }
        return 0;
    }
    private function addOrderHistory($data)
    {
        $orderController = new OrdersController();
        $orderController->addNewOrder($data);
    }
}

?>