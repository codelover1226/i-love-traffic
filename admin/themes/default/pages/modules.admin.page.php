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
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "themes/default/incs/header.theme.php";
$modulesController = new ModulesController();
$modulesList = $modulesController->listModules();
?>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h2 class="text-xl"><?= $title ?></h2>
    <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
            <a href="index.php" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span>Pages & Settings</span>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <a href="modules.php" class="text-primary hover:underline">Modules</a>
        </li>
        <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
            <span><?= $title ?></span>
        </li>
    </ul>
    <div class="grid grid-cols-1 gap-6 pt-5 lg:grid-cols-1">
        <div class="panel">
            <div class="mb-5">
                <div class="grid gap-6 md:grid-cols-1 xl:grid-cols-1">
                    <div class="panel h-full">
                        <div class="-m-5 mb-5 flex items-start border-b border-[#e0e6ed] p-5 dark:border-[#1b2e4b]">
                            <div class="font-semibold">
                                <h6>i-LoveTraffic</h6>
                                <p class="mt-1 text-xs text-white-dark">Version NSMS3.0</p>
                            </div>
                        </div>
                        <div>
                            <div class="pb-8 text-white-dark">
                                Want more feautures ? Need custom module development ? Contact us
                            </div>
                            <div class="absolute bottom-0 -mx-5 flex w-full items-center justify-between p-5">
                                <a href="https://nsmailerscript.com" target="_blank" class="flex items-center rounded-md bg-success/30 px-1.5 py-1 text-xs text-success hover:shadow-[0_10px_20px_-10px] hover:shadow-success">Contact
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ltr:ml-1.5 rtl:mr-1.5 rtl:rotate-180">
                                        <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="mb-5">
                <div class="grid gap-6 md:grid-cols-1 xl:grid-cols-2">
                    <?php if (!empty($modulesList)) : ?>
                        <?php foreach ($modulesList as $module) : ?>
                            <?php
                            if (file_exists("../modules/$module")) {
                                require_once "../modules/" . $module;
                            }
                            ?>
                            <?php if (isset($moduleInfo) && !empty($moduleInfo)) : ?>

                                <div class="panel h-full">
                                    <div class="-m-5 mb-5 flex items-start border-b border-[#e0e6ed] p-5 dark:border-[#1b2e4b]">
                                        <div class="font-semibold">
                                            <h6><?= isset($moduleInfo["moduleName"]) ? $moduleInfo["moduleName"] : "" ?>
                                                <?php if (isset($moduleInfo["installStatus"]) && $moduleInfo["installStatus"] == 1) : ?>
                                                    <span class="badge bg-primary">Installed</span>
                                                <?php endif; ?>
                                            </h6>
                                            <p class="mt-1 text-xs text-white-dark"><?= isset($moduleInfo["moduleVersion"]) ? $moduleInfo["moduleVersion"] : "" ?></p>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="table-responsive">
                                            <table class="table-hover">
                                                <tbody>
                                                    <tr>
                                                        <td>Developer</td>
                                                        <td><?= isset($moduleInfo["moduleDeveloper"]) ? $moduleInfo["moduleDeveloper"] : "" ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Organization</td>
                                                        <td><?= isset($moduleInfo["moduleOrganization"]) ? $moduleInfo["moduleOrganization"] : "" ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Website</td>
                                                        <td><?= isset($moduleInfo["moduleWebsite"]) ? $moduleInfo["moduleWebsite"] : "" ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Description</td>
                                                        <td><?= isset($moduleInfo["moduleDescription"]) ? $moduleInfo["moduleDescription"] : "" ?></td>
                                                    </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php if (isset($moduleInfo["installLink"]) && isset($moduleInfo["installStatus"]) && $moduleInfo["installStatus"] == 2) : ?>
                                            <div class="absolute bottom-0 -mx-5 flex w-full items-center justify-between p-5">
                                                <a href="<?= $moduleInfo["installLink"] ?>" class="flex items-center rounded-md bg-success/30 px-1.5 py-1 text-xs text-success hover:shadow-[0_10px_20px_-10px] hover:shadow-success">Install
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ltr:ml-1.5 rtl:mr-1.5 rtl:rotate-180">
                                                        <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
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