<?php
ob_start();
session_start();
if(isset($_SESSION["email_website"])){
    header("Location: {$_SESSION['email_website']}");
}