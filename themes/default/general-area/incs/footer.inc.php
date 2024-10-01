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
<footer class="footer-section bg_img" data-background="themes/default/general-area/assets/images/footer/footer-bg.jpg">
  <div class="container">
    <div class="footer-top padding-top padding-bottom">
      <div class="logo">
        <a href="#0">
          <img src="themes/default/general-area/assets/images/logo/logo.png" alt="logo">
        </a>
      </div>
      
    </div>
    <div class="footer-bottom">
      <ul class="footer-link">
        <li>
          <a href="about.php">About</a>
        </li>
        <li>
          <a href="faqs.php">FAQs</a>
        </li>
        <li>
          <a href="tos.php">Terms of Service</a>
        </li>
        <li>
          <a href="policy.php">Privacy Policy</a>
        </li>
      </ul>
    </div>
    <div class="copyright">
      <p>
      <div class="copyright">
        &copy; <?= date("Y") ?> <strong> <span><?= $siteSettingsData["site_title"] ?></span></strong>. All Rights Reserved
      </div>
      
        </p>
      </div>
    </div>
</footer>

<script src="themes/default/general-area/assets/js/jquery-3.3.1.min.js"></script>
<script src="themes/default/general-area/assets/js/modernizr-3.6.0.min.js"></script>
<script src="themes/default/general-area/assets/js/plugins.js"></script>
<script src="themes/default/general-area/assets/js/bootstrap.min.js"></script>
<script src="themes/default/general-area/assets/js/magnific-popup.min.js"></script>
<script src="themes/default/general-area/assets/js/jquery-ui.min.js"></script>
<script src="themes/default/general-area/assets/js/wow.min.js"></script>
<script src="themes/default/general-area/assets/js/waypoints.js"></script>
<script src="themes/default/general-area/assets/js/nice-select.js"></script>
<script src="themes/default/general-area/assets/js/owl.min.js"></script>
<script src="themes/default/general-area/assets/js/counterup.min.js"></script>
<script src="themes/default/general-area/assets/js/paroller.js"></script>
<script src="themes/default/general-area/assets/js/main.js"></script>
</body>

</html>