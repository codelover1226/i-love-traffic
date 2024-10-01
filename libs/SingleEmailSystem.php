<?php
$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    exit("");
}
include_once __DIR__ . "/../configs/config.php";
require_once __DIR__ . "/../vendor/autoload.php";
class SingleEmailSystem
{
    public static function sendEmail($from_email, $from_name, $to_email, $to_name, $subject, $message)
    {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = SMTP_PROTOCOL;
            $mail->Port = SMTP_PORT;
            $mail->setFrom($from_email, $from_name);
            $mail->addAddress($to_email, $to_name);
            $mail->addReplyTo($from_email, $from_name);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->send();
        } catch (PHPMailer\PHPMailer\Exception $e) {
        }
    }
}

?>