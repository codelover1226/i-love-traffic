<?php ?>
<li class="menu nav-item">
    <button type="button" class="nav-link group" :class="{'active' : activeDropdown === 'payment-menu'}" @click="activeDropdown === 'payment-menu' ? activeDropdown = null : activeDropdown = 'payment-menu'">
        <div class="flex items-center">
            <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M12,2C6.48,2 2,6.48 2,12s4.48,10 10,10s10-4.48 10-10S17.52,2 12,2z M12,19c-3.86,0-7-3.14-7-7s3.14-7 7-7s7,3.14 7,7S15.86,19 12,19z" />
                <path d="M12.5,7H11v5.25l2.25,1.29l0.75-1.23l-1.5-0.86V7z" />
                <circle cx="12" cy="17" r="1" />
                <circle cx="18" cy="12" r="1" />
                <circle cx="12" cy="7" r="1" />
                <circle cx="6" cy="12" r="1" />
                <circle cx="12" cy="12" r="3" />
            </svg>
            <span class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Payment Gateways</span>
        </div>
        <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'payment-menu'}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </button>
    <ul x-cloak x-show="activeDropdown === 'payment-menu'" x-collapse class="sub-menu text-gray-500">
        <li>
            <a href="binance-payment.php">Binance</a>
        </li>
        <li>
            <a href="coinbase-payment.php">Coinbase Commerce</a>
        </li>
        <li>
            <a href="mollie-payment.php">Mollie</a>
        </li>
        <li>
            <a href="paypal-payment.php">PayPal</a>
        </li>
        <li>
            <a href="stripe-payment.php">Stripe</a>
        </li>
    </ul>
</li>