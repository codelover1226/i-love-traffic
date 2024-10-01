<?php

if (file_exists("../configs/config.php")) {
    require_once "../configs/config.php";
    $moduleInfo = ["moduleName" => "NSMS Voucher", "moduleVersion" => "2.0", "moduleDeveloper" => "Md. Moniruzzaman Prodhan (Noman Prodhan)", "moduleOrganization" => "i-LoveTraffic", "moduleWebsite" => "www.nsmailerscript.com", "installLink" => "modules.php?voucher=install", "installStatus" => 2, "moduleDescription" => "Offer vouchers/promo codes to your members and increase your activity."];
    if (HOST && DATABASE && USER && PASS) {
        try {
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false];
            $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE . ";charset=utf8mb4", USER, PASS, $options);
            $pdo->exec("SET character_set_client = 'utf8mb4'");
            $result = $pdo->query("SHOW TABLES LIKE 'ntk_nsms_vouchers'");
            $result2 = $pdo->query("SHOW TABLES LIKE 'ntk_nsms_voucher_used_history'");
            if (0 < $result->rowCount() && 0 < $result2->rowCount()) {
                $moduleInfo["installStatus"] = 1;
            } else {
                if (isset($_GET["voucher"]) && $_GET["voucher"] == "install") {
                    try {
                        $sql = file_get_contents("../modules/nsms-voucher/ntk_nsms_vouchers.sql");
                        $queries = explode(";\n", $sql);
                        foreach ($queries as $query) {
                            if (trim($query)) {
                                $pdo->exec($query);
                            }
                        }
                        $userMenuPath = "../themes/default/member-area/incs/header.inc.php";
                        $userModuleMenu = "<li class=\"nav-item\">\n";
                        $userModuleMenu .= "<a class=\"nav-link menu-link\" href=\"voucher.php\">\n";
                        $userModuleMenu .= "<i class=\"ri-store-2-line\"></i> <span>Voucher</span>\n";
                        $userModuleMenu .= "</a>\n";
                        $userModuleMenu .= "</li>\n";
                        if (file_exists($userMenuPath)) {
                            $userMenuContents = file_get_contents($userMenuPath);
                            if (strpos($userMenuContents, "<?php include_once \"themes/default/member-area/incs/sub-menus/468x60.menu.php\"; ?>") !== false && strpos($userMenuContents, "Membership Contest") === false) {
                                $updatedUserMenu = str_replace("<?php include_once \"themes/default/member-area/incs/sub-menus/468x60.menu.php\"; ?>", $userModuleMenu . "<?php include_once \"themes/default/member-area/incs/sub-menus/468x60.menu.php\"; ?>", $userMenuContents);
                                file_put_contents($userMenuPath, $updatedUserMenu);
                            }
                        }
                        $adminMenuPath = "../admin/themes/default/incs/sub-menus/modules.menu.php";
                        $adminModuleMenu = "<li><a href=\"vouchers.php\">Vouchers</a></li>\n";
                        if (file_exists($adminMenuPath)) {
                            $adminMenuContents = file_get_contents($adminMenuPath);
                            if (strpos($adminMenuContents, "</ul>") !== false && strpos($adminMenuContents, "Vouchers") === false) {
                                $updatedAdminMenu = str_replace("</ul>", $adminModuleMenu . "</ul>", $adminMenuContents);
                                file_put_contents($adminMenuPath, $updatedAdminMenu);
                            }
                        }
                        unlink("../modules/nsms-voucher/ntk_nsms_vouchers.sql");
                        $flag = ["success" => true, "message" => "Voucher module has been installed."];
                    } catch (PDOException $e) {
                    }
                }
            }
        } catch (PDOException $e) {
        }
    }
} else {
    exit;
}

?>