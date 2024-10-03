<?php ?>
<li class="nav-item">
    <a class="nav-link menu-link" href="#sidebarEmails" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEmails">
        <i class="ri-mail-send-line"></i> <span data-key="t-emails">Emails</span>
    </a>
    <div class="collapse menu-dropdown" id="sidebarEmails">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="emails.php?action=send" class="nav-link" data-key="t-calendar">Send Email </a>
            </li>
            <li class="nav-item">
                <a href="emails.php?action=schedule" class="nav-link" data-key="t-chat">Schedule Email </a>
            </li>
            <li class="nav-item">
                <a href="emails.php" class="nav-link" data-key="t-chat">Email History</a>
            </li>
            <li class="nav-item">
                <a href="emails.php?action=saved" class="nav-link" data-key="t-chat">Saved Emails</a>
            </li>
            <li class="nav-item">
                <a href="emails.php?action=add-draft" class="nav-link" data-key="t-chat">Save New Email</a>
            </li>
            <li class="nav-item">
                <a href="emails.php?action=auto-mail" class="nav-link" data-key="t-chat">Update Auto</a>
            </li>
            <li class="nav-item">
                <a href="received-mails.php" class="nav-link" data-key="t-chat">Received Emails</a>
            </li>
            <li class="nav-item">
                <a href="unread-mails.php" class="nav-link" data-key="t-chat">Unread Emails</a>
            </li>
        </ul>
    </div>
</li>