<?php
$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    header("Location: / ");
    exit;
}
require_once "../vendor/autoload.php";
if (file_exists("load_classes.php")) {
    require_once "load_classes.php";
} else {
    if (file_exists("../load_classes.php")) {
        require_once "../load_classes.php";
    }
}
class ModulesController extends Controller
{
    public function listModules()
    {
        if (file_exists("modules")) {
            $fileList = scandir("modules");
        } else {
            if (file_exists("../modules")) {
                $fileList = scandir("../modules");
            }
        }
        $phpFiles = [];
        if (!empty($fileList)) {
            foreach ($fileList as $file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if ($ext == "php") {
                    array_push($phpFiles, $file);
                }
            }
        }
        return $phpFiles;
    }
}

?>