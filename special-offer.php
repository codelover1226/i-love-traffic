<?php
ob_start();
session_start();
require_once "load_classes.php";
$membersController = new MembersController();
$membershipsController = new MembershipsController();
$membersController->verifyLoggedIn("logged_in");
if(isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])){
    $specialOfferPageController = new SpecialOfferPagesController();
    $pageDetails = $specialOfferPageController->getPageDetails($_GET["id"]);
    if(!empty($pageDetails)){
        $html_content = htmlspecialchars_decode($pageDetails["page_content"]);
        $membershipsList = $membershipsController->getActiveMembershipsList();
        $html_memberships = "";
        foreach ($membershipsList  as $memberships){
            if ($memberships["id"] == 1) {
                continue;
            }
            $html_membership = '
                <div class="" style="width: 350px; padding: 0 20px">
                    <div class="pricing-table" style="position: relative;">
                        <svg class="pricing-header" viewbox="0 0 100 55"> 
                            <path d="M 0,0 H100 V50 C55,0 50,80 0,40 Z" fill="#428dff" stroke="none"></path> 
                            <text fill="#fff" font-size="13" x="3" y="23"></text> 
                        </svg>
                        <div style="position: absolute; font-size: 30px; font-weight: 600; top: 30px; width: 100%; margin: auto;">'.$memberships["membership_title"].'</div>
                        <div class="price-value">
                        <div class="value"><span class="currency">$</span> <span class="amount">'.$memberships["price"].'</span></div>
                        </div>
        
                        <div class="pricing-content">
                        <ul>
                            <li>'.$membershipsController->getSubscriptionType()[$memberships["subscription_type"] - 1].'</li>
                            <li>'.$memberships["email_sending_limit"].' Email(s) Per Day</li>
                            <li>'.$memberships["bonus_email_credits"].' YEARLY bonus credits</li>
                            <li>Earn '.$memberships["credits_per_click"].' Credits per email click</li>
                            <li>'.$memberships["timer_seconds"].' Second Timer</li>
                            <li>'.$memberships["sales_commission"].'% Affiliate Commission</li>
                        </ul>
                        </div>
        
                        <div class="Sign-Up"><a href="check-out.php?id='.$memberships['id'].'&amp;type=membership">Buy Plan</a></div>
                    </div>
                </div>
            ';
            $html_memberships .= $html_membership;
        }
        $html_memberships_container = '
            <div class="container">
                <div class="row" style="width: 100%; display: flex; justify-content: center; flex-wrap: wrap;">
                '.$html_memberships.'
                </div>
            </div>
        ';
        $updated_content = str_replace('<membershipsection>', $html_memberships_container, $html_content);
        echo $updated_content;
        exit();
    }
}