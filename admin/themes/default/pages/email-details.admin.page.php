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
$emailsController = new EmailsController();
$id = $_GET["more"];
$emailDetails = $emailsController->getMailDetails($id);
$adminController->adminCSRFTokenGen();
?>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h2 class="text-xl"><?= $title ?></h2>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="index.php" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Mailer & Members</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Mailer</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
        <a href="email-list.php" class="text-primary hover:underline">Emails</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">

        <div class="panel">
            <?php if ($emailDetails["suspend_status"] != 1) : ?>
                <a href="email-list.php?suspend=<?= $emailDetails['id'] ?>&token=<?= $adminController->getAdminCSRFToken() ?>"><button type="button" class="btn btn-danger">Suspend</button>
                </a>
            <?php else : ?>
                <a href="email-list.php?unsuspend=<?= $emailDetails['id'] ?>&token=<?= $adminController->getAdminCSRFToken() ?>"><button type="button" class="btn btn-success">Unsuspend</button>
                </a>
            <?php endif; ?>
            <div class="mb-5">
                <?php if (empty($emailDetails)) : ?>
                    <div class="alert alert-danger">Couldn't find the email.</div>
                <?php else : ?>
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample">
                                <div class="form-group">
                                    <label for="noticeContent">Email Subject</label>
                                    <input type="text" class="form-input" value="<?= base64_decode($emailDetails["email_subject"]) ?>" />
                                    <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                                </div>
                                <div class="form-group">
                                    <label for="noticeContent">Email Body</label>
                                    <textarea class="form-input" rows="35" id="email_content"><?= base64_decode($emailDetails["email_body"]) ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="noticeContent">Website Link</label>
                                    <input type="text" class="form-input" value="<?= base64_decode($emailDetails["website_link"]) ?>" />
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
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