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
class CoopUrlsController extends Controller
{
    private $db;
    private $model;
    private $coopUrlClicksTable = "ntk_coop_url_clicks";

    public function __construct()
    {
        $this->model = new CoopUrlsModel();
        $this->db = dbConnection::getDBInstance();
    }

	public function coopUrlClicksOrigin($id, $username)
	{
		$query = 'SELECT *, COUNT(*) as total_clicks FROM ' . $this->coopUrlClicksTable . ' ' . "\n" . '        WHERE id = ? AND username = ? GROUP BY visitor_origin ORDER BY total_clicks DESC';
		$handler = $this->getDBConnection()->prepare($query);
		$handler->bindValue(1, $this->model->filter($id));
		$handler->bindValue(2, $this->model->filter($username));
		$handler->execute();
		return $handler->fetchAll(PDO::FETCH_ASSOC);
	}

    public function coopUrlClicksCountry($id, $username)
    {
        $query = "SELECT *, COUNT(*) as total_clicks FROM " . $this->coopUrlClicksTable . " \n        WHERE coop_url_id = ? AND username = ? GROUP BY visitor_country ORDER BY total_clicks DESC";
        $handler = $this->getDBConnection()->prepare($query);
        $handler->bindValue(1, $this->model->filter($id));
        $handler->bindValue(2, $this->model->filter($username));
        $handler->execute();
        return $handler->fetchAll(PDO::FETCH_ASSOC);
    }
    public function trackingSystem($coopUrl)
    {
        $visitor_ip = $_SERVER['REMOTE_ADDR'];
        $parsed_url = parse_url($_SERVER['HTTP_REFERER']);

        // Get the host/domain name
        if (isset($parsed_url['host'])) {
            $visitor_origin = $parsed_url['host'];
            // echo $domain; // Output: i-lovetraffic.online
        } else {
            $visitor_origin = "unknown/direct";
            // echo "Host not found in the URL.";
        }

        $api_link = 'http://ip-api.com/php/' . $_SERVER['REMOTE_ADDR'];

        if (!function_exists('curl_init')) {
            exit('CURL is not installed!');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        $ip_info = @unserialize($output);
        $user_country = '';
        if ($ip_info && ($ip_info['status'] == 'success')) {
            $user_country = $ip_info['country'];
        }

        $this->insertData($this->coopUrlClicksTable, ['coop_url_id' => $coopUrl['id'], 'username' => $coopUrl['username'], 'visitor_country' => $user_country, 'visitor_origin' => $visitor_origin, 'visitor_ip' => $visitor_ip, 'visitor_timestamp' => time()]);
    }
    protected function insertData($table, $datas)
    {
        $data_keys = array_keys($datas);
        $data_values = array_values($datas);
        $query = "INSERT INTO " . $table . " (id ," . implode(", ", $data_keys) . ") VALUES (NULL, " . implode(", ", array_fill(0, count($data_values), "?")) . ")";
        $stmt = $this->db->prepare($query);
        for ($i = 0; $i < count($data_values); $i++) {
            $data_values[$i] = $this->model->filter($data_values[$i]);
        }
        return $stmt->execute($data_values);
    }

    public function CoopUrlsList()
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalCoopUrls();
            $total_offset = ceil($total / 30);
            if ($_GET["page"] - 1 < 0) {
                $offset = 0;
            } else {
                if ($total_offset < $_GET["page"] - 1) {
                    $offset = 0;
                } else {
                    $offset = ($_GET["page"] - 1) * 30;
                }
            }
        }
        return $this->model->coopUrlsList(30, $offset);
    }
    public function lastCoopUrl()
    {
        return $this->model->lastCoopUrl();
    }
    public function lastCoopUrlsList($limit)
    {
        return $this->model->lastCoopUrlsList($limit);
    }
    public function coopUrlsPgination()
    {
        return $this->pagination(30, $this->totalCoopUrls(), "web-coop-urls.php");
    }
    public function totalCoopUrls()
    {
        return $this->model->totalCoopUrls();
    }
    public function adStatus()
    {
        return ["Pending", "Approved", "Banned"];
    }
    public function activateAd() // pend
    {
        if (isset($_GET["activate"]) && isset($_GET["token"]) && !empty($_GET["activate"]) && !empty($_GET["token"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_GET["activate"]) || $_GET["activate"] < 1) {
                return ["success" => false, "message" => "Invalid banner ad."];
            }
            $this->model->updateCoopUrl(["status" => 1], $_GET["activate"]);
            return ["success" => true, "message" => "Coop Url has been pended."];
        }
    }
    public function pauseAd() // approve
    {
        if (isset($_GET["pause"]) && isset($_GET["token"]) && !empty($_GET["pause"]) && !empty($_GET["token"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_GET["pause"]) || $_GET["pause"] < 1) {
                return ["success" => false, "message" => "Invalid banner ad."];
            }
            $this->model->updateCoopUrl(["status" => 2], $_GET["pause"]);
            return ["success" => true, "message" => "Coop Url has been approved."];
        }
    }
    public function banAd()
    {
        if (isset($_GET["ban"]) && isset($_GET["token"]) && !empty($_GET["ban"]) && !empty($_GET["token"])) {
            $adminController = new AdminController();
            if ($adminController->getAdminCSRFToken() != $_GET["token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_GET["ban"]) || $_GET["ban"] < 1) {
                return ["success" => false, "message" => "Invalid banner ad."];
            }
            $this->model->updateCoopUrl(["status" => 3], $_GET["ban"]);
            return ["success" => true, "message" => "Coop Url has been banned."];
        }
    }
    public function getCoopUrlDetails($id)
    {
        return $this->model->getCoopUrlDetails($id);
    }
    public function getCoopUrl()
    {
        $bannerData = $this->model->getCoopUrl();
        if (!empty($bannerData)) {
            echo "<div class='bannerFrameWeb-468-60'>
                    <a href=\"banner-click.php?id=" . $bannerData["id"] . "\" target=\"_blank\">
                        <img src=\"" . $bannerData["image_link"] . "\" height=\"60\" width=\"468\" />
                    </a>
                    <a class=\"adByBottom\" href=\"https://i-lovetraffic.online/\" target=\"_blank\">
                        I-Love Traffic
                    </a>
                </div>";
        } else {
            echo "<a href=\"index.php\"><img src=\"images/468x60.jpg\" height=\"60\" width=\"468\" /></a>";
        }
    }
    public function getRandomCoopUrlDetails()
    {
        return $this->model->getCoopUrl();
    }
    public function coopUrlClick()
    {
        if (isset($_GET["id"]) && !empty($_GET["id"]) && is_numeric($_GET["id"])) {
            $coopUrlData = $this->getCoopUrlDetails($_GET["id"]);
            if (empty($coopUrlData)) {
                echo "Invalid link";
                exit;
            }
            $this->model->increaseCoopUrlClicks($_GET["id"]);
            header("Location: " . $coopUrlData["ad_link"]);
            exit;
        }
        echo "Invalid link";
        exit;
    }
    public function totalBannerViews()
    {
        return $this->model->totalBannerViews()["total_views"];
    }
    public function totalUserCoopUrls($username)
    {
        return $this->model->totalUserCoopUrls($username);
    }
    public function userCoopUrlsPagination($username)
    {
        return $this->pagination(30, $this->totalUserCoopUrls($username), "web-banners.php");
    }
    public function userCoopUrlsList($username)
    {
        $offset = 0;
        if (isset($_GET["page"]) && !empty($_GET["page"]) && is_numeric($_GET["page"])) {
            $total = $this->totalCoopUrls();
            $total_offset = ceil($total / 30);
            if ($_GET["page"] - 1 < 0) {
                $offset = 0;
            } else {
                if ($total_offset < $_GET["page"] - 1) {
                    $offset = 0;
                } else {
                    $offset = ($_GET["page"] - 1) * 30;
                }
            }
        }
        return $this->model->userCoopUrlsList(30, $offset, $username);
    }
    public function updateUserAd($username, $id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["image_link"]) && isset($_POST["ad_link"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $coopUrlDetails = $this->getCoopUrlDetails($id);
            if (empty($coopUrlDetails) || $coopUrlDetails["username"] != $username) {
                return ["success" => false, "message" => "Couldn't find the ad."];
            }
            $membersController = new MembersController();
            if (empty($_POST["image_link"]) || empty($_POST["ad_link"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!filter_var($_POST["ad_link"], FILTER_VALIDATE_URL)) {
                return ["success" => false, "message" => "Invalid ad link. Please enter a valid URL"];
            }
            if (!filter_var($_POST["image_link"], FILTER_VALIDATE_URL) || !$this->is_url_image($_POST["image_link"])) {
                return ["success" => false, "message" => "Invalid banner link. Please enter a valid banner URL"];
            }
            $this->model->updateCoopUrl(["image_link" => $_POST["image_link"], "ad_link" => $_POST["ad_link"]], $id);
            return ["success" => true, "message" => "Your ad  has been updated."];
        }
    }
    public function addUserAdCredits($username, $userInfo, $id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["credits"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            $adDetails = $this->getcoopUrlDetails($id);
            if (empty($adDetails) || $adDetails["username"] != $username) {
                return ["success" => false, "message" => "Couldn't find the ad."];
            }
            $membersController = new MembersController();
            if (empty($_POST["credits"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "Please enter credits amount you want to add."];
            }
            if ($membersController->getUserCSRFToken() != $_POST["csrf_token"]) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["credits"]) || $_POST["credits"] < 1 || !is_int(intval($_POST["credits"]))) {
                return ["success" => false, "message" => "Invalid credits."];
            }
            if ($userInfo["banner_credits"] < $_POST["credits"]) {
                return ["success" => false, "message" => "You don't have enough credits."];
            }
            $this->model->increaseCoopUrlCredits($id, $_POST["credits"]);
            $membersController->deductMemberCoopUrlCredits($username, $_POST["credits"]);
            return ["success" => true, "message" => intval($_POST["credits"]) . " credits has been assign to the ad."];
        }
    }
    public function pauseUserAd($username, $id)
    {
        if (isset($_GET["pause"]) && isset($_GET["token"])) {
            $membersController = new MembersController();
            if (!empty($_GET["pause"]) && !empty($_GET["token"]) && is_numeric($_GET["pause"]) && 0 < $_GET["pause"] && $membersController->getUserCSRFToken() == $_GET["token"]) {
                $adDetails = $this->getcoopUrlDetails($id);
                if ($username != $adDetails["username"]) {
                    return ["success" => false, "message" => "Couldn't find the ad."];
                }
                if ($adDetails["status"] == 3) {
                    return ["success" => false, "message" => "Your ad has been banned by admin. You can change the ad status."];
                }
                $this->model->updateCoopUrl(["status" => 2], $id);
                return ["success" => true, "message" => "Your ad has been paused."];
            }
        }
    }
    public function activateUserAd($username, $id)
    {
        if (isset($_GET["activate"]) && isset($_GET["token"])) {
            $membersController = new MembersController();
            if (!empty($_GET["activate"]) && !empty($_GET["token"]) && is_numeric($_GET["activate"]) && 0 < $_GET["activate"] && $membersController->getUserCSRFToken() == $_GET["token"]) {
                $adDetails = $this->getcoopUrlDetails($id);
                if ($username != $adDetails["username"]) {
                    return ["success" => false, "message" => "Couldn't find the ad."];
                }
                if ($adDetails["status"] == 3) {
                    return ["success" => false, "message" => "Your ad has been banned by admin. You can change the ad status."];
                }
                $this->model->updateCoopUrl(["status" => 1], $id);
                return ["success" => true, "message" => "Your ad has been activated."];
            }
        }
    }
    public function deleteUserAd($username, $id)
    {
        if (isset($_GET["delete"]) && isset($_GET["token"])) {
            $membersController = new MembersController();
            if (!empty($_GET["delete"]) && !empty($_GET["token"]) && is_numeric($_GET["delete"]) && 0 < $_GET["delete"] && $membersController->getUserCSRFToken() == $_GET["token"]) {
                $adDetails = $this->getcoopUrlDetails($id);
                if ($username != $adDetails["username"] || empty($adDetails)) {
                    return ["success" => false, "message" => "Couldn't find the banner ad."];
                }
                $membersController->increaseMemberCoopUrlCredits($username, $adDetails["credits"]);
                $this->model->deleteAd($id);
                return ["success" => true, "message" => "Your ad has been deleted."];
            }
        }
    }
    public function removeCoopUrlCredits($username, $id)
    {
        if (isset($_POST["remove_credits"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed."];
            }
            $membersController = new MembersController();
            if (empty($_POST["remove_credits"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "Please enter credits amount you want to remove."];
            }
            if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!is_numeric($_POST["remove_credits"]) || $_POST["remove_credits"] < 1) {
                return ["success" => false, "message" => "Invalid credits."];
            }
            $coopUrlDetails = $this->getcoopUrlDetails($id);
            if ($coopUrlDetails["username"] != $username) {
                return ["success" => false, "message" => "Couldn't find the ad."];
            }
            if ($coopUrlDetails["credits"] < $_POST["remove_credits"]) {
                return ["success" => false, "message" => "Not enough credits to remove."];
            }
            $remainCredits = $coopUrlDetails["credits"] - $_POST["remove_credits"];
            if ($remainCredits < 1) {
                $remainCredits = 0;
            }
            $this->model->updateCoopUrl(["credits" => $remainCredits], $id);
            var_dump($remainCredits);
            if (0 < $remainCredits) {
                $membersController->increaseMemberCoopUrlCredits($username, $_POST["remove_credits"]);
            }
            return ["success" => true, "message" => "Credits has been removed from the ad."];
        }
    }
    public function addUserCoopUrl($username)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ad_link"]) && isset($_POST["csrf_token"])) {
            if ($this->arrayCheck($_POST)) {
                return ["success" => false, "message" => "Array not allowed here."];
            }
            $membersController = new MembersController();
            if (empty($_POST["ad_link"]) || empty($_POST["csrf_token"])) {
                return ["success" => false, "message" => "All fields are required."];
            }
            if ($_POST["csrf_token"] != $membersController->getUserCSRFToken()) {
                return ["success" => false, "message" => "Invalid request."];
            }
            if (!filter_var($_POST["ad_link"], FILTER_VALIDATE_URL)) {
                return ["success" => false, "message" => "Invalid website link."];
            }
            $this->model->addCoopUrl(["username" => $username, "ad_link" => $_POST["ad_link"], "credits" => 0, "total_views" => 0, "total_clicks" => 0, "creation_time" => time(), "status" => 1]);
            return ["success" => true, "message" => "Your coop url has been added and pended. Now you can assign credits."];
        }
    }
    protected function getDBConnection()
    {
        return $this->db;
    }
}

?>