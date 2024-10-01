<?php ?>
<li class="menu nav-item">
    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'affiliates-menu'}" @click="activeDropdown === 'affiliates-menu' ? activeDropdown = null : activeDropdown = 'affiliates-menu'">
        <div class="flex items-center">
            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M17,11h-1V8c0-1.1-0.9-2-2-2h-2V5c0-1.1-0.9-2-2-2H8C6.9,3 6,3.9 6,5v1H5C3.9,6 3,6.9 3,8v2h1v5H3v2c0,1.1,0.9,2,2,2h2v-1h5v1h2c1.1,0,2-0.9,2-2v-2h-1V11z M8,5h2v2H8V5z M8,19H6v-2h2V19z M18,17h-2v-2h2V17z M10,15H8v-2h2V15z M16,15h-2v-2h2V15z M14,11H10V8h4V11z" />
            </svg>

            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Affiliates</span>
        </div>
        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'affiliates-menu'}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </button>
    <ul x-cloak x-show="activeDropdown === 'affiliates-menu'" x-collapse class="sub-menu text-gray-500">
        <li>
            <a href="affiliate-settings.php">Affiliate Settings</a>
        </li>
        <li>
            <a href="withdrawal-requests.php">Withdraw Request</a>
        </li>
        <li>
            <a href="splash-pages.php">Splash Pages</a>
        </li>
        <li>
            <a href="add-reward.php">Add Reward</a>
        </li>
        <li>
            <a href="banners.php">Promotional Banners</a>
        </li>
        <li>
            <a href="promotional-emails.php">Promotional Emails</a>
        </li>
    </ul>
</li>