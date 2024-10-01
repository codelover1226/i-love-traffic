<?php ?>
<li class="menu nav-item">
    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'banner-ads'}" @click="activeDropdown === 'banner-ads' ? activeDropdown = null : activeDropdown = 'banner-ads'">
        <div class="flex items-center">
            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M21,4H3C1.9,4,1,4.9,1,6v12c0,1.1,0.9,2,2,2h18c1.1,0,2-0.9,2-2V6C23,4.9,22.1,4,21,4z M21,18H3V6h18V18z" />
                <polygon points="4,7 20,7 17,12 20,17 4,17 7,12" />
            </svg>


            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Banner Ads</span>
        </div>
        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'banner-ads'}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </button>
    <ul x-cloak x-show="activeDropdown === 'banner-ads'" x-collapse class="sub-menu text-gray-500">
        <li>
            <a href="web-banner-ads.php">468x60 Banners</a>
        </li>
        <li>
            <a href="web-banner-ads-160-600.php">160x600 Banners</a>
        </li>
        <li>
            <a href="web-banner-ads-728-90.php">728x90 Banners</a>
        </li>
        <li>
            <a href="small-banner-ads.php">125x125 Banners</a>
        </li>
        <li>
            <a href="web-banner-ads-600-400.php">600x400 Banners</a>
        </li>
        <li>
            <a href="banner-ad-settings.php">Conversion Rate</a>
        </li>
        <li>
            <a href="banner-publisher.php">Banner Publisher Settings</a>
        </li>
    </ul>
</li>