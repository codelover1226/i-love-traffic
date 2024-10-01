<?php ?>
<li class="menu nav-item">
    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'system-menu'}" @click="activeDropdown === 'system-menu' ? activeDropdown = null : activeDropdown = 'system-menu'">
        <div class="flex items-center">
            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M19.14,12.94l1.43-1.17l-0.75-2.3l-1.97,0.5c-0.39-0.28-0.8-0.52-1.23-0.71l-0.3-2.14h-2.64l-0.3,2.14c-0.43,0.19-0.84,0.43-1.23,0.71l-1.97-0.5l-0.75,2.3l1.43,1.17c-0.04,0.3-0.07,0.61-0.07,0.94s0.03,0.64,0.07,0.94l-1.43,1.17l0.75,2.3l1.97-0.5c0.39,0.28,0.8,0.52,1.23,0.71l0.3,2.14h2.64l0.3-2.14c0.43-0.19,0.84-0.43,1.23-0.71l1.97,0.5l0.75-2.3l-1.43-1.17c0.04-0.3,0.07-0.61,0.07-0.94S19.18,13.24,19.14,12.94z M12,15.5c-1.93,0-3.5-1.57-3.5-3.5s1.57-3.5,3.5-3.5s3.5,1.57,3.5,3.5S13.93,15.5,12,15.5z" />
            </svg>
            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">System Settings</span>
        </div>
        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'system-menu'}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </button>
    <ul x-cloak x-show="activeDropdown === 'system-menu'" x-collapse class="sub-menu text-gray-500">
        <li>
            <a href="site-settings.php">Website Settings</a>
        </li>
        <li>
            <a href="seo-options.php">SEO Settings</a>
        </li>
        <li>
            <a href="banned-emails.php">Banned Emails</a>
        </li>
        <li>
            <a href="banned-domains.php">Banned Domains</a>
        </li>
    </ul>
</li>