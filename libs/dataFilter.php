<?php
$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    exit("");
}
class dataFilter
{
    public function filter($data)
    {
        $data = stripcslashes($data);
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

?>