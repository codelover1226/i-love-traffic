<?php ?>
<li class="menu nav-item">
    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'members'}" @click="activeDropdown === 'members' ? activeDropdown = null : activeDropdown = 'members'">
        <div class="flex items-center">
            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                <circle cx="17.5" cy="7.5" r="2.5" />
                <path d="M18 14c-1.66 0-3 1.34-3 3v2h6v-2c0-1.66-1.34-3-3-3z" />
            </svg>

            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Members</span>
        </div>
        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'members'}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </button>
    <ul x-cloak x-show="activeDropdown === 'members'" x-collapse class="sub-menu text-gray-500">
        <li>
            <a href="members.php">Members</a>
        </li>
        <li>
            <a href="mass-mail.php">Mass Mail</a>
        </li>
        <li>
            <a href="single-mail.php">Single Mail</a>
        </li>
        <li>
            <a href="memberships.php">Memberships</a>
        </li>
    </ul>
</li>
