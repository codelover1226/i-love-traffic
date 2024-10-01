<?php ?>
<li class="menu nav-item">
    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'pages-menu'}" @click="activeDropdown === 'pages-menu' ? activeDropdown = null : activeDropdown = 'pages-menu'">
        <div class="flex items-center">
            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M19,4H5C3.9,4 3,4.9 3,6v12c0,1.1 0.9,2 2,2h14c1.1,0 2-0.9 2-2V6C21,4.9 20.1,4 19,4z M19,18H5V6h14v12z" />
                <path d="M17,9H7v2h10V9z" />
                <path d="M17,12H7v2h10v-2z" />
                <path d="M7,15h10v2H7v-2z" />
            </svg>
            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Pages</span>
        </div>
        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'pages-menu'}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </button>
    <ul x-cloak x-show="activeDropdown === 'pages-menu'" x-collapse class="sub-menu text-gray-500">
        <li>
            <a href="special-offer-pages.php">Special Offer Pages</a>
        </li>
        <li>
            <a href="about-page.php">About Page</a>
        </li>
        <li>
            <a href="tos-page.php">ToS Page</a>
        </li>
        <li>
            <a href="privacy-page.php">Privacy Page</a>
        </li>
        <li>
            <a href="faqs-page.php">FAQs Page</a>
        </li>
    </ul>
</li>