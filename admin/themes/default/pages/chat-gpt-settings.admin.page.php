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
$chatGPTController = new ChatGPTController();
$flag = $chatGPTController->updateSettings();
$chatGPTSettings = $chatGPTController->getSettings();
$adminController->adminCSRFTokenGen();
$siteSettingsController = new SiteSettingsController();
$siteSettingsData = $siteSettingsController->getSettings();
?>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h2 class="text-xl"><?= $title ?></h2>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="index.php" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Artificial Intelligence</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-2">
        <div class="panel">
            <div class="mb-5">
                <form action="" method="POST">
                    <label for="noticeContent">API Key</label>
                    <input type="text" class="form-input" name="api_key" placeholder="OpenAI API Key" value="<?= $chatGPTSettings['api_key'] ?>">
                    <input type="hidden" name="admin_csrf_token" value="<?= $adminController->getAdminCSRFToken() ?>">

                    <label for="noticeContent">Max Tokens</label>
                    <input type="text" class="form-input" name="max_tokens" placeholder="Max tokens for per prompt and response" value="<?= $chatGPTSettings['max_tokens'] ?>">
                    <label for="exampleSelectGender">OpenAI Model</label>
                    <small>We recommend you to use the "gpt-4-1106-preview" model. When we tested, it was giving good result.</small>
                    <select class="form-select text-white-dark" id="open_ai_model" name="open_ai_model">

                        <?php foreach ($chatGPTController->openAIModels() as $model) : ?>
                            <option value="<?= $model ?>" <?= $chatGPTSettings["open_ai_model"] == $model ? "selected" : "" ?>><?= $model ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br>
                    <label for="exampleSelectGender">ChatGPT Status</label>
                    <select class="form-select text-white-dark" id="status" name="chatGPTStatus">
                        <option value="1" <?= $chatGPTSettings["chatGPTStatus"] == 1 ? "selected" : "" ?>>Enable</option>
                        <option value="2" <?= $chatGPTSettings["chatGPTStatus"] == 2 ? "selected" : "" ?>>Disable</option>
                    </select>
                    <button type="submit" class="btn btn-primary mt-6">Update</button>
                </form>
                <br>
                You can find your OpenAI API Key here at <a href="https://platform.openai.com/api-keys" target="_blank">https://platform.openai.com/api-keys</a>
                <br>
                <br>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <td>OpenAI Model</td>
                            <td>Cost</td>
                        </thead>

                        <tbody>
                            <?php foreach ($chatGPTController->openAIModelPrices() as $modelName => $cost) : ?>
                                <tr>
                                    <td><?= $modelName ?></td>
                                    <td><?= $cost ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <br>
                You can find updated pricing here at <a href="https://openai.com/pricing" target="_blank">https://openai.com/pricing</a>
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

<?php require_once "themes/default/incs/footer.theme.php"; ?>