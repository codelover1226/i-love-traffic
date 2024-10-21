<?php ?>
<li class="menu nav-item">
    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'coop-urls'}" @click="activeDropdown === 'coop-urls' ? activeDropdown = null : activeDropdown = 'coop-urls'">
        <div class="flex items-center">
            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M21,4H3C1.9,4,1,4.9,1,6v12c0,1.1,0.9,2,2,2h18c1.1,0,2-0.9,2-2V6C23,4.9,22.1,4,21,4z M21,18H3V6h18V18z" />
                <polygon points="4,7 20,7 17,12 20,17 4,17 7,12" />
            </svg>


            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Coop Urls</span>
        </div>
        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'coop-urls'}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </button>
    <ul x-cloak x-show="activeDropdown === 'coop-urls'" x-collapse class="sub-menu text-gray-500">
        <li>
            <a href="web-coop-urls.php">All coop urls</a>
        </li>
        <li>
            <a href="coop-url-settings.php">Conversion Rate</a>
        </li>
    </ul>
</li>