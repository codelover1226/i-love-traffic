<?php ?>
<li class="menu nav-item">
    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'downline'}" @click="activeDropdown === 'downline' ? activeDropdown = null : activeDropdown = 'downline'">
        <div class="flex items-center">
            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle opacity="0.5" cx="15" cy="6" r="3" fill="currentColor" />
                <ellipse opacity="0.5" cx="16" cy="17" rx="5" ry="3" fill="currentColor" />
                <circle cx="9.00098" cy="6" r="4" fill="currentColor" />
                <ellipse cx="9.00098" cy="17.001" rx="7" ry="4" fill="currentColor" />
            </svg>

            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Downline Programs</span>
        </div>
        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'downline'}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </button>
    <ul x-cloak x-show="activeDropdown === 'downline'" x-collapse class="sub-menu text-gray-500">
        <li>
            <a href="downline-programs.php">Downline Programs</a>
        </li>
        <li>
            <a href="add-downline-program.php">Add Link</a>
        </li>
        <li>
            <a href="downline-builder-settings.php">Settings</a>
        </li>
        <li>
            <a href="downline-builder-search.php">Search</a>
        </li>
    </ul>
</li>