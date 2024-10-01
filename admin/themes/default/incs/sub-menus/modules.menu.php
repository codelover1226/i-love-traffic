<?php ?>
<li class="menu nav-item">
    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'modules-menu'}" @click="activeDropdown === 'modules-menu' ? activeDropdown = null : activeDropdown = 'modules-menu'">
        <div class="flex items-center">
            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M21.04,12.13l-1.2-1.6c0.06-0.22,0.16-0.42,0.16-0.65s-0.09-0.43-0.16-0.65l1.2-1.6c0.05-0.07,0.07-0.16,0.07-0.24s-0.02-0.17-0.07-0.24l-2-2.67c-0.1-0.14-0.25-0.23-0.42-0.23h-2.4c-0.17,0-0.33,0.09-0.42,0.23l-1.2,1.6c-0.22-0.06-0.43-0.16-0.65-0.16s-0.43,0.09-0.65,0.16l-1.2-1.6c-0.09-0.14-0.25-0.23-0.42-0.23h-2.4c-0.17,0-0.32,0.09-0.42,0.23l-2,2.67c-0.05,0.07-0.07,0.16-0.07,0.24s0.02,0.17,0.07,0.24l1.2,1.6c-0.06,0.22-0.16,0.42-0.16,0.65s0.09,0.43,0.16,0.65l-1.2,1.6c-0.05,0.07-0.07,0.16-0.07,0.24s0.02,0.17,0.07,0.24l2,2.67c0.1,0.14,0.25,0.23,0.42,0.23h2.4c0.17,0,0.33-0.09,0.42-0.23l1.2-1.6c0.22,0.06,0.43,0.16,0.65,0.16s0.43-0.09,0.65-0.16l1.2,1.6c0.09,0.14,0.25,0.23,0.42,0.23h2.4c0.17,0,0.32-0.09,0.42-0.23l2-2.67c0.05-0.07,0.07-0.16,0.07-0.24S21.09,12.2,21.04,12.13z M12,16.5c-2.48,0-4.5-2.02-4.5-4.5s2.02-4.5,4.5-4.5s4.5,2.02,4.5,4.5S14.48,16.5,12,16.5z" />
            </svg>
            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Modules</span>
        </div>
        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'modules-menu'}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </button>
    <ul x-cloak x-show="activeDropdown === 'modules-menu'" x-collapse class="sub-menu text-gray-500">
        <li>
            <a href="modules.php">Modules</a>
        </li>
    <li><a href="vouchers.php">Vouchers</a></li>
<li><a href="link-trackers.php">Link Tracker</a></li>
</ul>
</li>