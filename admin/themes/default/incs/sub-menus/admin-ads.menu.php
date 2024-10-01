<?php ?>
<li class="menu nav-item">
    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'admin-ads-menu'}" @click="activeDropdown === 'admin-ads-menu' ? activeDropdown = null : activeDropdown = 'admin-ads-menu'">
        <div class="flex items-center">
            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M12,1 L3,5v6c0,5.55 3.84,10.74 9,12 5.16-1.26 9-6.45 9-12V5l-9-4zm0,2.18l6,2.7v4.74c0,4.04-2.68,7.8-6,9.04-3.32-1.24-6-4.99-6-9.04V5.88l6-2.7zM11,6v5H8l4,4 4-4h-3V6h-2z" />
            </svg>


            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Admin Ads</span>
        </div>
        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'admin-ads-menu'}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </button>
    <ul x-cloak x-show="activeDropdown === 'admin-ads-menu'" x-collapse class="sub-menu text-gray-500">
        <li>
            <a href="admin-ads.php">Ads List</a>
        </li>
        <li>
            <a href="add-admin-ad.php">Add New Ad</a>
        </li>
    </ul>
</li>