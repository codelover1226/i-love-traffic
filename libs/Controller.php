<?php
$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    exit("");
}
class Controller
{
    public function pagination($limit, $total, $base_url)
    {
        $total = $total;
        $total_offset = ceil($total / $limit);
        $current_page = 1;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] <= $total_offset) {
            $current_page = $_GET["page"];
        }
        if (1 < $total_offset) {
            echo "<div class=\"relative inline-flex align-middle\">";
            if (1 < $current_page) {
                echo "<a href=\"" . $base_url . "?page=" . ($current_page - 1) . "\">    <button type=\"button\" class=\"btn btn-dark ltr:rounded-r-none rtl:rounded-l-none\"><</button>\n                </a>";
            }
            echo "<a href=\"#\">    <button type=\"button\" class=\"btn btn-dark rounded-none\">" . $current_page . "</button>\n            </a>";
            if ($current_page < $total_offset) {
                echo "<a href=\"" . $base_url . "?page=" . ($current_page + 1) . "\">    <button type=\"button\" class=\"btn btn-dark ltr:rounded-l-none rtl:rounded-r-none\">></button>\n                </a>";
            }
            echo "</div>";
        }
    }
    public function arrayCheck($data)
    {
        $array_flag = false;
        foreach ($data as $var) {
            $array_flag = is_array($var);
            if ($array_flag) {
                return $array_flag;
            }
        }
    }
    public function is_url_image($url)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_NOBODY, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            $headers = [];
            foreach (explode("\n", $output) as $line) {
                $parts = explode(":", $line);
                if (count($parts) == 2) {
                    $headers[trim($parts[0])] = trim($parts[1]);
                }
            }
            if (isset($headers["Content-Type"]) && strpos($headers["Content-Type"], "image/") === 0) {
                return isset($headers["Content-Type"]) && strpos($headers["Content-Type"], "image/") === 0;
            }
            return isset($headers["content-type"]) && strpos($headers["content-type"], "image/") === 0;
        } catch (Exception $e) {
            return false;
        }
    }
    public function truncateString($str)
    {
        if (10 < strlen($str)) {
            return substr($str, 0, 5) . "..." . substr($str, -5);
        }
        return $str;
    }
    public function truncateStringLarge($str)
    {
        if (50 < strlen($str)) {
            return substr($str, 0, 50) . "........." . substr($str, -50);
        }
        return $str;
    }
}

?>