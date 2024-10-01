<?php

if (file_exists("../configs/config.php")) {
    require_once "../configs/config.php";
    $moduleInfo = ["moduleName" => "NSMS Link Tracker", "moduleVersion" => "2.0", "moduleDeveloper" => "Md. Moniruzzaman Prodhan (Noman Prodhan)", "moduleOrganization" => "i-LoveTraffic", "moduleWebsite" => "www.nsmailerscript.com", "installLink" => "modules.php?link_tracker=install", "installStatus" => 2, "moduleDescription" => "Offer link tracking service to your members."];
    if (HOST && DATABASE && USER && PASS) {
        try {
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false];
            $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE . ";charset=utf8mb4", USER, PASS, $options);
            $pdo->exec("SET character_set_client = 'utf8mb4'");
            $result = $pdo->query("SHOW TABLES LIKE 'ntk_shorten_links'");
            $result2 = $pdo->query("SHOW TABLES LIKE 'ntk_nsms_voucher_used_history'");
            $result3 = $pdo->query("SHOW TABLES LIKE 'ntk_shorten_link_settings'");
            if (0 < $result->rowCount() && 0 < $result2->rowCount() && 0 < $result3->rowCount()) {
                $moduleInfo["installStatus"] = 1;
            } else {
                if (isset($_GET["link_tracker"]) && $_GET["link_tracker"] == "install") {
                    try {
                        $sql = file_get_contents("../modules/nsms-link-tracker/ntk_link_tracker.sql");
                        $queries = explode(";\n", $sql);
                        foreach ($queries as $query) {
                            if (trim($query)) {
                                $pdo->exec($query);
                            }
                        }
                        $userMenuPath = "../themes/default/member-area/incs/header.inc.php";
                        $userModuleMenu = "<li class=\"nav-item\">\n";
                        $userModuleMenu = "<a class=\"nav-link menu-link\" href=\"#sidebarLinkTracker\" data-bs-toggle=\"collapse\" role=\"button\" aria-expanded=\"false\" aria-controls=\"sidebarLinkTracker\">\n";
                        $userModuleMenu .= "<i class=\"ri-links-line\"></i> <span data-key=\"t-emails\">Link Tracker</span>\n";
                        $userModuleMenu .= "</a>\n";
                        $userModuleMenu .= " <div class=\"collapse menu-dropdown\" id=\"sidebarLinkTracker\">\n";
                        $userModuleMenu .= " <ul class=\"nav nav-sm flex-column\">\n";
                        $userModuleMenu .= " <li class=\"nav-item\">\n";
                        $userModuleMenu .= " <a href=\"link-tracker.php\" class=\"nav-link\" data-key=\"t-calendar\">Tracking Links</a>\n";
                        $userModuleMenu .= " </li>\n";
                        $userModuleMenu .= " <li class=\"nav-item\">\n";
                        $userModuleMenu .= " <a href=\"link-tracker.php?action=add\" class=\"nav-link\" data-key=\"t-chat\">New Link</a>\n";
                        $userModuleMenu .= " </li>\n";
                        $userModuleMenu .= " </ul>\n";
                        $userModuleMenu .= " </div>\n";
                        $userModuleMenu .= " </li>\n";
                        if (file_exists($userMenuPath)) {
                            $userMenuContents = file_get_contents($userMenuPath);
                            if (strpos($userMenuContents, "<?php include_once \"themes/default/member-area/incs/sub-menus/468x60.menu.php\"; ?>") !== false && strpos($userMenuContents, "Membership Contest") === false) {
                                $updatedUserMenu = str_replace("<?php include_once \"themes/default/member-area/incs/sub-menus/468x60.menu.php\"; ?>", $userModuleMenu . "<?php include_once \"themes/default/member-area/incs/sub-menus/468x60.menu.php\"; ?>", $userMenuContents);
                                file_put_contents($userMenuPath, $updatedUserMenu);
                            }
                        }
                        $adminMenuPath = "../admin/themes/default/incs/sub-menus/modules.menu.php";
                        $adminModuleMenu = "<li><a href=\"link-trackers.php\">Link Tracker</a></li>\n";
                        if (file_exists($adminMenuPath)) {
                            $adminMenuContents = file_get_contents($adminMenuPath);
                            if (strpos($adminMenuContents, "</ul>") !== false && strpos($adminMenuContents, "Link Tracker") === false) {
                                $updatedAdminMenu = str_replace("</ul>", $adminModuleMenu . "</ul>", $adminMenuContents);
                                file_put_contents($adminMenuPath, $updatedAdminMenu);
                            }
                        }
                        unlink("../modules/nsms-link-tracker/ntk_link_tracker.sql");
                        $flag = ["success" => true, "message" => "Link Tracker module has been installed."];
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