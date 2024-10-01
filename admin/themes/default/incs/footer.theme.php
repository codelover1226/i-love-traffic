<?php
/*
 *
 *
 *          Author          :   Noman Prodhan
 *          Email           :   hello@nomantheking.com
 *          Websites        :   www.nomantheking.com    www.nomanprodhan.com    www.nstechvalley.com
 *
 *
 */


$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    die("");
}
?>

<div class="p-6 pt-0 mt-auto text-center dark:text-white-dark ltr:sm:text-left rtl:sm:text-right">
    © <span id="footer-year"><?php echo date("Y") ?></span> All Rights Reserved. Powered By <a href="https://i-lovetraffic.online/" target="_blank">i-lovetraffic</a>
</div>
</div>
</div>

<script src="themes/default/assets/js/alpine-collaspe.min.js"></script>
<script src="themes/default/assets/js/alpine-persist.min.js"></script>
<script defer src="themes/default/assets/js/alpine-ui.min.js"></script>
<script defer src="themes/default/assets/js/alpine-focus.min.js"></script>
<script defer src="themes/default/assets/js/alpine.min.js"></script>
<script src="themes/default/assets/js/custom.js"></script>
<script defer src="themes/default/assets/js/apexcharts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>