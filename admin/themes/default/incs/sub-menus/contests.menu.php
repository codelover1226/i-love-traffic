<?php ?>
<li class="menu nav-item">
    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'contests-menu'}" @click="activeDropdown === 'contests-menu' ? activeDropdown = null : activeDropdown = 'contests-menu'">
        <div class="flex items-center">
            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M17,3H7C6.45,3 6,3.45 6,4v2c0,0.55 0.45,1 1,1v2c0,1.65 1.35,3 3,3s3-1.35 3-3V7c0.55,0 1-0.45 1-1V4c0-0.55-0.45-1-1-1zM8,6V5h8v1H8z M12,11c-1.1,0-2-0.9-2-2V7h4v2C14,10.1 13.1,11 12,11z" />
                <path d="M17.66,5H20v6c0,3.31-2.69,6-6,6h-4c-3.31,0-6-2.69-6-6V5h2.34C7.6,7.59 9.68,9 12,9s4.4-1.41 5.66-4z" />
            </svg>


            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Contests</span>
        </div>
        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'contests-menu'}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </button>
    <ul x-cloak x-show="activeDropdown === 'contests-menu'" x-collapse class="sub-menu text-gray-500">
        <li>
            <a href="activity-contest.php">Activity Contest</a>
        </li>
        <li>
            <a href="referral-contest.php">Referral Contest</a>
        </li>
        <li>
            <a href="sales-contest.php">Sales Contest</a>
        </li>
    </ul>
</li>