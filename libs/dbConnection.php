<?php
$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    exit("");
}
include_once __DIR__ . "/../configs/config.php";
class dbConnection
{
    public static $pdo;
    private static $host = HOST;
    private static $database = DATABASE;
    private static $user = USER;
    private static $pass = PASS;
    public function __construct()
    {
    }
    public static function getDBInstance()
    {
        if (!self::$pdo) {
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$database . ";charset=utf8";
            try {
                $link = new PDO($dsn, self::$user, self::$pass);
                // echo json_encode($link);
                self::$pdo = $link;
                return self::$pdo;
            } catch (Exception $e) {
                echo "Error" . $e;
            }
        } else {
            return self::$pdo;
        }
    }
}

?>