<?php

$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
require_once "themes/default/member-area/incs/header.inc.php";
$loginAdsAvailableDates = $loginAdsController->availableDates();
$loginAdsSettingsData = $loginAdsSettingsController->getSettings();
$productsList = $productsController->getActiveProducts();
$membershipsList = $membershipsController->getActiveMembershipsList();
?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <?php if (isset($flag) && isset($flag["success"])) : ?>
                    <?php if ($flag["success"] == true) : ?>
                        <div class="alert alert-success"><?= $flag["message"] ?></div>
                    <?php else : ?>
                        <div class="alert alert-danger"><?= $flag["message"] ?></div>
                    <?php endif; ?>
                <?php endif; ?>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card border border-primary">
                            <div class="card-header bg-transparent border-primary">
                                <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Email Credits</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                <form method="GET" class="formclass" action="check-out.php">
                                    <div class="form-group">
                                        <select class="form-select" name="id">
                                            <?= $emailCreditsPackageController->getEmailCreditsPackages() ?>
                                        </select>
                                    </div><br>
                                    <input type="hidden" name="type" value="credits" />
                                    <div class="form-group">
                                        <button class="btn btn-success">Buy Now</button><br><br><br><br>
                                    </div>
                                </form>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border border-primary">
                            <div class="card-header bg-transparent border-primary">
                                <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Login Spotlight Ad</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                <form method="GET" id="ptsuform" class="formclass" action="check-out.php">
                                    <div class="form-group">
                                        <select name="id" class="form-select" required>
                                            <?php if (empty($loginAdsAvailableDates)) : ?>
                                                <option value="">No available dates this month</option>
                                            <?php else : ?>
                                                <?php foreach ($loginAdsAvailableDates as $availableDate) : ?>
                                                    <option value="<?= $availableDate ?>"><?= $availableDate ?> - $<?= $loginAdsSettingsData["ad_price"] ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select><br>
                                        <input class="form-control" type="url" name="website_link" placeholder="Website link" required />
                                        <input type="hidden" name="type" value="loginads" />
                                    </div><br>
                                    <div class="form-group">
                                        <button class="btn btn-danger">Buy Now</button>
                                    </div>
                                </form>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card border border-success">
                        <div class="card-header bg-transparent border-success">
                            <h5 class="my-0 text-success"><i class="mdi mdi-bullseye-arrow me-3"></i>Memberships</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                            <div class="row">
                                <?php if (!empty($membershipsList)) : ?>
                                    <?php foreach ($membershipsList  as $memberships) : ?>
                                        <?php if ($memberships["id"] == 1) {
                                            continue;
                                        } ?>
                                        <div class="col-lg-4">
                                            <div class="card mb-1">
                                                <div class="card-body">
                                                    <a class="d-flex align-items-center" data-bs-toggle="collapse" href="#membership<?= $memberships['id']  ?>" role="button" aria-expanded="false" aria-controls="leadDiscovered3">
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="fs-15 mb-1"><?= $memberships["membership_title"] ?> </h6>
                                                            <p class="text-muted mb-0">$<?= $memberships["price"] ?></p>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="collapse border-top border-top-dashed show" id="membership<?= $memberships['id']  ?>">
                                                    <div class="card-body">
                                                        <ul class="list-unstyled vstack gap-2 mb-0">
                                                            <li>
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 avatar-xxs text-muted">
                                                                        <i class="ri-calendar-line"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0">Subscription Type</h6>
                                                                        <small class="text-muted"><?= $membershipsController->getSubscriptionType()[$memberships["subscription_type"] - 1] ?></small>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 avatar-xxs text-muted">
                                                                        <i class="ri-exchange-dollar-line"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0">Sales Commissions </h6>
                                                                        <small class="text-muted"><?= $memberships["sales_commission"] ?>%</small>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 avatar-xxs text-muted">
                                                                        <i class="ri-bug-line"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0">Access To ChatGPT ?</h6>
                                                                        <small class="text-muted"><?= $memberships["chat_gpt_access"] == 1 ? "Yes" : "No" ?></small>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <?php if($memberships["chat_gpt_access"] == 1): ?>
                                                                <li>
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 avatar-xxs text-muted">
                                                                        <i class="ri-bug-line"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0">ChatGPT Limit</h6>
                                                                        <small class="text-muted"><?= $memberships["chat_gpt_prompt_limit"] . " Times Per Month" ?></small>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                            <?php endif; ?>
                                                            <li>
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 avatar-xxs text-muted">
                                                                        <i class="ri-cursor-line"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0">Click Commissions </h6>
                                                                        <small class="text-muted"><?= $memberships["clicks_commission"] ?> Credits Per Referral Click</small>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 avatar-xxs text-muted">
                                                                        <i class="ri-alarm-line"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0">Timer</h6>
                                                                        <small class="text-muted"><?= $memberships["timer_seconds"] ?> Seconds</small>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 avatar-xxs text-muted">
                                                                        <i class="ri-mail-line"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0">Email Limit</h6>
                                                                        <small class="text-muted"><?= $memberships["email_sending_limit"] ?> Email(s) Per day</small>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 avatar-xxs text-muted">
                                                                        <i class="ri-mail-send-line"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0">Max Email Recipient</h6>
                                                                        <small class="text-muted"><?= $memberships["max_recipient"] ?> Users</small>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 avatar-xxs text-muted">
                                                                        <i class="ri-mail-add-line"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0">Credits Per Email Click</h6>
                                                                        <small class="text-muted"><?= $memberships["credits_per_click"] ?> Credits</small>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 avatar-xxs text-muted">
                                                                        <i class="ri-mail-add-line"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0">Bonus Email Credits</h6>
                                                                        <small class="text-muted"><?= $memberships["bonus_email_credits"] ?> Credits</small>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                            <li>
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 avatar-xxs text-muted">
                                                                        <i class="ri-file-text-line"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0">Bonus Text Ad Credits</h6>
                                                                        <small class="text-muted"><?= $memberships["bonus_text_ad_credits"] ?> Credits</small>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0 avatar-xxs text-muted">
                                                                        <i class="ri-image-line"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-0">Bonus Banner Ad Credits</h6>
                                                                        <small class="text-muted"><?= $memberships["bonus_banner_credits"] ?> Credits</small>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                    <div class="card-footer hstack gap-2">
                                                        <a href="check-out.php?id=<?= $memberships['id'] ?>&type=membership" class="btn btn-primary btn-sm w-100"><i class="ri-shopping-cart-2-line"></i> Buy Now</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card border border-primary">
                        <div class="card-header bg-transparent border-primary">
                            <h5 class="my-0 text-primary"><i class="mdi mdi-bullseye-arrow me-3"></i>Combo Offers</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                            <div class="row">
                                <?php if (!empty($productsList)) : ?>
                                    <?php foreach ($productsList  as $product) : ?>
                                        <div class="col-lg-6">
                                            <div class="card product">
                                                <div class="card-body">
                                                    <div class="row gy-3">
                                                        <div class="col-sm-auto">
                                                            <div class="avatar-lg bg-light rounded p-1">
                                                                <?php if ($product["special_offer"] == 1) : ?>
                                                                    <img src="images/special_offer.webp" alt="" class="img-fluid d-block">
                                                                <?php else : ?>
                                                                    <img src="images/combo_offer.png" alt="" class="img-fluid d-block">
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm">
                                                            <h5 class="fs-14 text-truncate"><a href="check-out.php?id=<?= $product['id'] ?>&type=combo" class="text-body"><?= $product["product_title"] ?> </a></h5>

                                                            <ul class="list-inline text-muted">
                                                                <li class="list-inline-item"><?= htmlspecialchars_decode($product["product_description"]) ?></li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-sm-auto">
                                                            <div class="text-lg-end">
                                                                <p class="text-muted mb-1">Item Price:</p>
                                                                <h5 class="fs-14">$<span class="product-price"><?= $product["product_price"] ?></span></h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                    <div class="row align-items-center gy-3">
                                                        <div class="col-sm">
                                                            <div class="d-flex flex-wrap my-n1">
                                                                <div>
                                                                    <a href="check-out.php?id=<?= $product['id'] ?>&type=combo" class="d-block text-body p-1 px-2"><i class="ri-delete-bin-fill text-muted align-bottom me-1"></i> Buy Now</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php require_once "themes/default/member-area/incs/footer.inc.php"; ?>