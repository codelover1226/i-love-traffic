<?php ?>
<li class="menu nav-item">
    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'login-spotlights'}" @click="activeDropdown === 'login-spotlights' ? activeDropdown = null : activeDropdown = 'login-spotlights'">
        <div class="flex items-center">
            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M12,2C8.13,2 5,5.13 5,9c0,1.65 0.69,3.15 1.78,4.22L2,17.98V22h4.02l4.95-4.95C12.29,17.74 14.08,18 16,18c3.87,0 7-3.13 7-7s-3.13-7-7-7zM7,9c0-2.76 2.24-5 5-5s5,2.24 5,5s-2.24,5-5,5S7,11.76 7,9z" />
            </svg>

            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Login Spotlight</span>
        </div>
        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'login-spotlights'}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </button>
    <ul x-cloak x-show="activeDropdown === 'login-spotlights'" x-collapse class="sub-menu text-gray-500">
        <li>
            <a href="login-ads.php">Ads List</a>
        </li>
        <li>
            <a href="add-login-ad.php">Add to User</a>
        </li>
        <li>
            <a href="login-ad-settings.php">Settings</a>
        </li>
    </ul>
</li>