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
$membershipsController = new MembershipsController();
$massMailController = new MassMailController();
$memberships = $membershipsController->getAllMemberships();
$flag = $massMailController->addNewMail();
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
            <span>Members</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
        <a href="mass-mail.php" class="text-primary hover:underline">Mass Mails</a>
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
                        <label for="noticeContent">Subject</label>
                        <input type="text" class="form-input" name="email_subject" placeholder="Enter email subject">
                        <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Send to </label>
                        <select class="form-input" name="membership">
                            <option value="0">All Member</option>
                            <?php if (!empty($memberships)) : ?>
                                <?php foreach ($memberships as $membershipData) : ?>
                                    <option value="<?= $membershipData["id"] ?>">
                                        <?= $membershipData["membership_title"] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="noticeContent">Email Body</label>
                        <textarea class="form-input" id="email_content" rows="12" name="email_body" placeholder="Email content"></textarea>
                    </div>
                    <br>
                    <label class="badge badge-danger">Macros</label><br>
                    <p>Member's First Name : {FIRSTNAME}</p>
                    <p>Member's Last Name : {LASTNAME}</p>
                    <hr>
                    <button type="submit" class="btn btn-primary mt-6">Add</button>
                </form>
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