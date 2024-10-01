<?php
ob_start();
session_start();
require_once "load_classes.php";
$membersController = new MembersController();
if (
    isset($_POST["email_subject"]) &&
    isset($_POST["email_body"]) &&
    isset($_POST["website_link"]) &&
    isset($_POST["credits_assign"]) &&
    isset($_POST["schedule_date"]) &&
    isset($_POST["schedule_hour"]) &&
    isset($_POST["schedule_minute"]) &&
    isset($_POST["csrf_token"])
) {
    if (
        empty($_POST["email_subject"]) ||
        empty($_POST["email_body"]) ||
        empty($_POST["website_link"]) ||
        empty($_POST["credits_assign"]) ||
        empty($_POST["schedule_date"]) ||
        empty($_POST["csrf_token"])
    ) {
        echo "Invalid request.";
        exit();
    } else if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
        echo "CSRF Token mismatch.";
        exit();
    } else {
        $flag = 2;
        $_SESSION["email_website"] = $_POST["website_link"];
    }
} else if (
    isset($_POST["email_subject"]) &&
    isset($_POST["email_body"]) &&
    isset($_POST["website_link"]) &&
    isset($_POST["credits_assign"]) &&
    isset($_POST["csrf_token"])
) {
    if (
        empty($_POST["email_subject"]) ||
        empty($_POST["email_body"]) ||
        empty($_POST["website_link"]) ||
        empty($_POST["credits_assign"]) ||
        empty($_POST["csrf_token"])
    ) {
        echo "Invalid request.";
        exit();
    } else if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
        echo "CSRF Token mismatch.";
        exit();
    } else {
        $flag = 1;
        $_SESSION["email_website"] = $_POST["website_link"];
    }
}
?>

<?php if (isset($flag) && $flag == 1) : ?>
    <html>

    <head>
        <title>Website Checking...</title>
    </head>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            overflow: hidden;
        }

        .main-header {
            background: rgb(0, 5, 36);
            background: linear-gradient(90deg, rgba(0, 5, 36, 1) 0%, rgba(9, 70, 121, 1) 61%, rgba(3, 81, 217, 1) 100%, rgba(0, 212, 255, 1) 100%);
            color: #ffffff;
            padding: 10px;
            height: 100px;
            width: 100%;
        }

        .header-text {
            font-family: Arial, Helvetica, sans-serif;
            color: #ffffff;
            padding-top: 15px;
        }

        .header-button-area {
            position: absolute;
            float: right;
        }

        .btn {
            box-sizing: border-box;
            appearance: none;
            background-color: transparent;
            border: 2px solid #00aeff;
            border-radius: 0.6em;
            color: #ffffff;
            cursor: pointer;
            display: flex;
            align-self: center;
            font-size: 1rem;
            font-weight: 20;
            line-height: 1;
            padding: 1.2em 2.8em;
            text-decoration: none;
            text-align: center;
            text-transform: uppercase;
            font-family: 'Montserrat', sans-serif;
            font-weight: 20;
        }


        .iframe-style {
            width: 100%;
            height: 100%;
        }

        .column-50 {
            float: left;
            width: 50%;
            padding: 10px;
            height: 100px;
            overflow: hidden;
        }

        .column-25 {
            float: left;
            width: 25%;
            padding: 10px;
            height: 100px;
        }

        .row:after {
            display: table;
            clear: both;
        }
    </style>

    <body>
        <div class="main-header">
            <div class="row">
                <div class="column-50">
                    <div class="header-text">Checking your website...make sure it loads perfectly. </div>
                </div>
                <div class="row">
                    <div class="column-25">
                        <div class="header-button-area">
                            <form action="emails.php?action=send" method="POST">
                                <input type="hidden" name="email_subject" value='<?= $_POST["email_subject"] ?>' />
                                <input type="hidden" name="email_body" value='<?= $_POST["email_body"] ?>' />
                                <input type="hidden" name="website_link" value='<?= $_POST["website_link"] ?>' />
                                <input type="hidden" name="credits_assign" value="<?= $_POST['credits_assign'] ?>" />
                                <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>" />
                                <input type="hidden" name="add_queue" value="add_queue" />
                                <button class="btn" type="submit">Send Mail</button>
                            </form>
                        </div>
                    </div>
                    <div class="column-25">
                        <div class="header-button-area"><a href="dashboard.php"><button class="btn" type="submit">Exit</button></a></div>
                    </div>
                </div>
            </div>
        </div>
        <iframe class="iframe-style" src="website-check-redirect.php"></iframe>
    </body>

    </html>

<?php elseif (isset($flag) && $flag == 2) : ?>
    <html>

    <head>
        <title>Website Checking...</title>
    </head>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            overflow: hidden;
        }

        .main-header {
            background: rgb(0, 5, 36);
            background: linear-gradient(90deg, rgba(0, 5, 36, 1) 0%, rgba(9, 70, 121, 1) 61%, rgba(3, 81, 217, 1) 100%, rgba(0, 212, 255, 1) 100%);
            color: #ffffff;
            padding: 10px;
            height: 100px;
            width: 100%;
        }

        .header-text {
            font-family: Arial, Helvetica, sans-serif;
            color: #ffffff;
            padding-top: 15px;
        }

        .header-button-area {
            position: absolute;
            float: right;
        }

        .btn {
            box-sizing: border-box;
            appearance: none;
            background-color: transparent;
            border: 2px solid #00aeff;
            border-radius: 0.6em;
            color: #ffffff;
            cursor: pointer;
            display: flex;
            align-self: center;
            font-size: 1rem;
            font-weight: 20;
            line-height: 1;
            padding: 1.2em 2.8em;
            text-decoration: none;
            text-align: center;
            text-transform: uppercase;
            font-family: 'Montserrat', sans-serif;
            font-weight: 20;
        }


        .iframe-style {
            width: 100%;
            height: 100%;
        }

        .column-50 {
            float: left;
            width: 50%;
            padding: 10px;
            height: 100px;
            overflow: hidden;
        }

        .column-25 {
            float: left;
            width: 25%;
            padding: 10px;
            height: 100px;
        }

        .row:after {
            display: table;
            clear: both;
        }
    </style>

    <body>
        <div class="main-header">
            <div class="row">
                <div class="column-50">
                    <div class="header-text">Checking your website...make sure it loads perfectly. </div>
                </div>
                <div class="row">
                    <div class="column-25">
                        <div class="header-button-area">
                            <form action="emails.php?action=schedule" method="POST">
                                <input type="hidden" name="email_subject" value='<?= $_POST["email_subject"] ?>' />
                                <input type="hidden" name="email_body" value='<?= $_POST["email_body"] ?>' />
                                <input type="hidden" name="website_link" value="<?= $_POST['website_link'] ?>" />
                                <input type="hidden" name="credits_assign" value="<?= $_POST['credits_assign'] ?>" />
                                <input type="hidden" name="schedule_date" value="<?= $_POST['schedule_date'] ?>" />
                                <input type="hidden" name="schedule_hour" value="<?= $_POST['schedule_hour'] ?>" />
                                <input type="hidden" name="schedule_minute" value="<?= $_POST['schedule_minute'] ?>" />
                                <input type="hidden" name="csrf_token" value="<?= $membersController->getUserCSRFToken() ?>" />
                                <input type="hidden" name="add_schedule" value="add_schedule" />
                                <button class="btn" type="submit">Schedule Mail</button>
                            </form>
                        </div>
                    </div>
                    <div class="column-25">
                        <div class="header-button-area"><a href="dashboard.php"><button class="btn" type="submit">Exit</button></a></div>
                    </div>
                </div>
            </div>
        </div>
        <iframe class="iframe-style" src="website-check-redirect.php"></iframe>
    </body>

    </html>
<?php endif; ?>