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

require_once "themes/default/incs/header.theme.php";
$promotionalsEmailsController = new PromotionalEmailsController();
$flag = $promotionalsEmailsController->AddPromotionalEmails();
$adminController->adminCSRFTokenGen();
?>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h2 class="text-xl"><?= $title ?></h2>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="index.php" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Store & Affiliates</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Affiliates</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <a href="promotional-emails.php" class="text-primary hover:underline">Promotional Emails</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">
            <div class="mb-5">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="noticeContent">Email Subject</label>
                        <input type="text" class="form-input" name="email_subject" placeholder="Enter a subject for your email">
                        <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Email Body</label>
                        <textarea id="email_content" class="form-input" rows="30" name="email_body" placeholder="Enter your email content"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-6">Add</button>
                </form>
            </div>
        </div>
        <div class="panel">
            <h5 style="font-size: 25px;">Available Macros & Functions</h5>
            <br>
            <p>
                Use these macros for splash pages<br>
                Referral Link (Homepage)<br>
                {REF_HOME}<br><br>
                Referral Link (Register Page)<br>
                {REF_REG}<br><br><br>
            </p>
        </div>
    </div>
</div>
<?php if (isset($flag) && isset($flag["success"])) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($flag["success"] == true) : ?>
                Swal.fire({
                    title: 'Success!',
                    text: '<?= addslashes($flag["message"]) ?>',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            <?php else : ?>
                Swal.fire({
                    title: 'Error!',
                    text: '<?= addslashes($flag["message"]) ?>',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>
        });
    </script>
<?php endif; ?>
<script src="../vendor/ckeditor5/build/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#email_content'), {

            toolbar: {
                items: [
                    'heading',
                    'alignment',
                    'fontColor',
                    'fontFamily',
                    'fontSize',
                    '|',
                    'bold',
                    'underline',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    'horizontalLine',
                    '|',
                    'specialCharacters',
                    'subscript',
                    'superscript',
                    'htmlEmbed',
                    'removeFormat',
                    'undo',
                    'redo'
                ]
            },
            language: 'en',
            licenseKey: '',



        })
        .then(editor => {
            window.editor = editor;




        })
        .catch(error => {

        });
</script>
<?php require_once "themes/default/incs/footer.theme.php"; ?>