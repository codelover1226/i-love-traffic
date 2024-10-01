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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="themes/default/assets/js/custom.js"></script>
<script>
  async function showAlertWithMessage(message) {
    new window.Swal({
      title: message,
      width: 600,
      padding: '7em',
      customClass: 'background-modal',
      background: '#fff url(' + ('themes/default/assets/images/sweet-bg.jpg') + ') no-repeat 100% 100%',
    });
  }
</script>

</body>

</html>