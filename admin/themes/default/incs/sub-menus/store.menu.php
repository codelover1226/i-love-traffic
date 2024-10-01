<?php ?>
<li class="menu nav-item">
    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'store-menu'}" @click="activeDropdown === 'store-menu' ? activeDropdown = null : activeDropdown = 'store-menu'">
        <div class="flex items-center">
            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M22,6h-4.2L15,2H9L6.2,6H2v16h20V6z M11.25,7.5H12.75V12H11.25V7.5z M4,20V8h16v12H4z M6.75,7.5H8.25V12H6.75V7.5z M9.75,18H14.25V14H9.75V18z M15.75,7.5H17.25V12H15.75V7.5z" />
            </svg>
            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Store</span>
        </div>
        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'store-menu'}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </button>
    <ul x-cloak x-show="activeDropdown === 'store-menu'" x-collapse class="sub-menu text-gray-500">
        <li>
            <a href="products.php">Products</a>
        </li>
        <li>
            <a href="add-product.php">Add New Product</a>
        </li>
    </ul>
</li>