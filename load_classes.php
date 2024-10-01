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

$currentPage = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($_SERVER['REQUEST_METHOD'] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    header("Location: / ");
    exit();
}
spl_autoload_register(function ($class) {
    if (file_exists(__DIR__ . "/libs/{$class}.php")) {
        require_once __DIR__ . "/libs/{$class}.php";
    }
    if (file_exists(__DIR__ . "/models/{$class}.php")) {
        require_once __DIR__ . "/models/{$class}.php";
    }
    if (file_exists(__DIR__ . "/controllers/{$class}.php")) {
        require_once __DIR__ . "/controllers/{$class}.php";
    }
});
