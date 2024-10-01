<?php
$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    header("Location: / ");
    exit;
}
if (file_exists("load_classes.php")) {
    require_once "load_classes.php";
} else {
    if (file_exists("../load_classes.php")) {
        require_once "../load_classes.php";
    }
}
class WebsiteThemeLoaderController extends Controller
{
    public function loadWebsitePage($page, $pageTitle = NULL, $area = NULL)
    {
        $websiteSettingsController = new SiteSettingsController();
        $websiteSettings = $websiteSettingsController->getSettings();
        if ($pageTitle != NULL) {
            $title = $pageTitle;
        }
        if ($area == "member") {
            if (file_exists("themes/" . $websiteSettings["website_theme"] . "/member-area/pages/" . $page . ".page.php")) {
                require_once "themes/" . $websiteSettings["website_theme"] . "/member-area/pages/" . $page . ".page.php";
            } else {
                echo "Couldn't find the member area page in theme directory.";
            }
        } else {
            if ($area == "login") {
                if (file_exists("themes/" . $websiteSettings["website_theme"] . "/login-area/pages/" . $page . ".page.php")) {
                    require_once "themes/" . $websiteSettings["website_theme"] . "/login-area/pages/" . $page . ".page.php";
                } else {
                    echo "Couldn't find the login area page in theme directory.";
                }
            } else {
                if (file_exists("themes/" . $websiteSettings["website_theme"] . "/general-area/pages/" . $page . ".page.php")) {
                    require_once "themes/" . $websiteSettings["website_theme"] . "/general-area/pages/" . $page . ".page.php";
                } else {
                    echo "Couldn't find the page in theme directory.";
                }
            }
        }
    }
}

?>