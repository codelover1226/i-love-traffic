<?php ?>
<li class="nav-item">
    <a class="nav-link menu-link" href="#sidebarAffiliateMessaging" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAffiliateMessaging">
        <i class="ri-mail-add-line"></i> <span data-key="t-emails">Affiliate Messaging</span>
    </a>
    <div class="collapse menu-dropdown" id="sidebarAffiliateMessaging">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="affiliate-messages.php" class="nav-link" data-key="t-calendar">Inbox [<?= $affiliateMessagingController->totalAffiliateUnreadMessage($userInfo["username"]) ?>]</a>
            </li>
            <li class="nav-item">
                <a href="compose-affiliate-message.php" class="nav-link" data-key="t-calendar">Compose Message</a>
            </li>
            <li class="nav-item">
                <a href="sent-affiliate-messages.php" class="nav-link" data-key="t-chat">Sent Messages</a>
            </li>
        </ul>
    </div>
</li>