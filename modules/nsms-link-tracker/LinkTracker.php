<?php

echo ' ' . "\n";
$currentPage = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on') ? 'https' : 'http') . ('://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
if (($_SERVER['REQUEST_METHOD'] == 'GET') && (strcmp(basename($currentPage), basename(__FILE__)) == 0)) {
	header('Location: / ');
	exit();
}

if (file_exists('load_classes.php')) {
	require_once 'load_classes.php';
}
else if (file_exists('../load_classes.php')) {
	require_once '../load_classes.php';
}
else if (file_exists('../../load_classes.php')) {
	require_once '../../load_classes.php';
}

class LinkTracker extends Model
{
	private $shortenLinkTable = 'ntk_shorten_links';
	private $shortenLinkClicksTable = 'ntk_shorten_link_clicks';
	private $shortenLinkSettingsTable = 'ntk_shorten_link_settings';

	public function addShortenLink($username, $membershipId)
	{
		if (isset($_POST['website_link']) && isset($_POST['csrf_token'])) {
			$membersController = new MembersController();
			$shortenLinkSettings = $this->getSettings();
			if (!empty($shortenLinkSettings) && ($shortenLinkSettings['system_power'] != 1)) {
				return ['success' => false, 'message' => 'Link tracker has been disabled. Please contact admin for more details.'];
			}
			if (($membershipId == 1) && ($shortenLinkSettings['free_member_limit'] <= $this->totalUserShortenLink($username))) {
				return ['success' => false, 'message' => 'You have reached your max tracking link limit. Please upgrade your account.'];
			}
			else if (($membershipId != 1) && ($shortenLinkSettings['paid_member_limit'] <= $this->totalUserShortenLink($username))) {
				return ['success' => false, 'message' => 'You have reached your max tracking link limit.'];
			}
			if (empty($_POST['website_link']) || empty($_POST['csrf_token'])) {
				return ['success' => false, 'message' => 'Please enter a valid website link.'];
			}
			else if ($membersController->getUserCSRFToken() != $_POST['csrf_token']) {
				return ['success' => false, 'message' => 'Invalid request.'];
			}
			else if (!filter_var($_POST['website_link'], FILTER_VALIDATE_URL)) {
				return ['success' => false, 'message' => 'Please enter a valid website link.'];
			}
			else {
				$websiteSettingsController = new SiteSettingsController();
				$websiteSettingsData = $websiteSettingsController->getSettings();
				$websiteLinkDetails = $this->websiteLinkDetails($_POST['website_link'], $username);

				if (!empty($websiteLinkDetails)) {
					return ['success' => true, 'message' => 'You have already created a tracking link for the link.', 'tracking_link' => $websiteSettingsData['installation_url'] . 'l.php?l=' . $websiteLinkDetails['shorten_code']];
				}

				$shortenCode = $this->generateShortenCode();
				$shortenCodeDetails = $this->getShortenCodeDetails($shortenCode);

				while (!empty($shortenCodeDetails)) {
					$shortenCode = $this->generateShortenCode();
					$shortenCodeDetails = $this->getShortenCodeDetails($shortenCode);
				}

				$this->insertData($this->shortenLinkTable, ['username' => $username, 'actual_link' => $_POST['website_link'], 'shorten_code' => $shortenCode, 'created_at' => time(), 'total_visits' => 0]);
				return ['success' => true, 'message' => 'Tracking link has been created.', 'tracking_link' => $websiteSettingsData['installation_url'] . 'l.php?l=' . $shortenCode];
			}
		}
	}

	public function shortenLinkDetails($shortenCode, $username)
	{
		$query = 'SELECT * FROM ' . $this->shortenLinkTable . ' WHERE shorten_code = ? AND username = ? LIMIT 1';
		$handler = $this->getDBConnection()->prepare($query);
		$handler->bindValue(1, $this->filter($shortenCode));
		$handler->bindValue(2, $this->filter($username));
		$handler->execute();
		return $handler->fetch(PDO::FETCH_ASSOC);
	}

	public function shortenLinkClicksCountry($shortenCode, $username)
	{
		$query = 'SELECT *, COUNT(*) as total_clicks FROM ' . $this->shortenLinkClicksTable . ' ' . "\n" . '        WHERE shorten_code = ? AND username = ? GROUP BY visitor_country ORDER BY total_clicks DESC';
		$handler = $this->getDBConnection()->prepare($query);
		$handler->bindValue(1, $this->filter($shortenCode));
		$handler->bindValue(2, $this->filter($username));
		$handler->execute();
		return $handler->fetchAll(PDO::FETCH_ASSOC);
	}

	public function shortenLinkClicksOrigin($shortenCode, $username)
	{
		$query = 'SELECT *, COUNT(*) as total_clicks FROM ' . $this->shortenLinkClicksTable . ' ' . "\n" . '        WHERE shorten_code = ? AND username = ? GROUP BY visitor_origin ORDER BY total_clicks DESC';
		$handler = $this->getDBConnection()->prepare($query);
		$handler->bindValue(1, $this->filter($shortenCode));
		$handler->bindValue(2, $this->filter($username));
		$handler->execute();
		return $handler->fetchAll(PDO::FETCH_ASSOC);
	}

	public function websiteLinkDetails($websiteLink, $username)
	{
		$query = 'SELECT * FROM ' . $this->shortenLinkTable . ' WHERE username = ? AND actual_link = ? LIMIT 1';
		$handler = $this->getDBConnection()->prepare($query);
		$handler->bindValue(1, $this->filter($username));
		$handler->bindValue(2, $this->filter($websiteLink));
		$handler->execute();
		return $handler->fetch(PDO::FETCH_ASSOC);
	}

	public function deleteAllClicks($shortenCode)
	{
		$this->deleteData($this->shortenLinkClicksTable, $shortenCode, 'shorten_code');
	}

	public function deleteUserShortenLink($username, $token)
	{
		if (isset($_GET['delete']) && !empty($_GET['delete'])) {
			$membersController = new MembersController();

			if ($membersController->getUserCSRFToken() == $token) {
				$shortenCodeDetails = $this->shortenLinkDetails($_GET['delete'], $username);

				if (empty($shortenCodeDetails)) {
					return ['success' => false, 'message' => 'Invalid tracking link.'];
				}
				else {
					$this->deleteData($this->shortenLinkTable, $_GET['delete'], 'shorten_code');
					$this->deleteAllClicks($_GET['delete']);
					return ['success' => true, 'message' => 'Tracking link and its statistics has been deleted.'];
				}
			}
		}
	}

	public function deleteShortenLink()
	{
		if (isset($_GET['delete']) && isset($_GET['token'])) {
			if (!empty($_GET['delete']) && !empty($_GET['token'])) {
				$adminController = new AdminController();

				if ($adminController->getAdminCSRFToken() == $_GET['token']) {
					$this->deleteData($this->shortenLinkTable, $_GET['delete'], 'shorten_code');
					$this->deleteAllClicks($_GET['delete']);
					return ['success' => true, 'message' => 'Tracking link and its statistics has been deleted.'];
				}
			}
		}
	}

	private function generateShortenCode()
	{
		return bin2hex(random_bytes(5));
	}

	public function getSettings()
	{
		return $this->getSingle($this->shortenLinkSettingsTable, 'id', 1);
	}

	public function totalUserShortenLink($username)
	{
		return $this->countWithCondition($this->shortenLinkTable, 'username', $username);
	}

	public function totalShortenLink()
	{
		return $this->countAll($this->shortenLinkTable);
	}

	public function userShortenLinkPagination($username)
	{
		$total = $this->totalUserShortenLink($username);
		$limit = 20;
		$total_offset = ceil($total / $limit);
		$current_page = 1;
		if (isset($_GET['page']) && !empty($_GET['page']) && is_numeric($_GET['page']) && ($_GET['page'] <= $total_offset)) {
			$current_page = $_GET['page'];
		}

		if (1 < $total_offset) {
			echo '<nav aria-label="Page navigation example">';
			echo '<ul class="pagination">';

			if (1 < $current_page) {
				echo '<li class="page-item"><a class="page-link" href="link-tracker.php?page=' . ($current_page - 1) . '">Previous</a></li>';
			}

			echo '<li class="page-item"><a class="page-link" href="#">' . $current_page . '</a></li>';

			if ($current_page < $total_offset) {
				echo '<li class="page-item"><a class="page-link" href="link-tracker.php?page=' . ($current_page + 1) . '">Next</a></li>';
			}

			echo '</ul>';
			echo '</nav>';
		}
	}

	public function shortenLinkPagination()
	{
		$total = $this->totalShortenLink();
		$limit = 20;
		$total_offset = ceil($total / $limit);
		$current_page = 1;
		if (isset($_GET['page']) && !empty($_GET['page']) && is_numeric($_GET['page']) && ($_GET['page'] <= $total_offset)) {
			$current_page = $_GET['page'];
		}

		if (1 < $total_offset) {
			echo '<nav aria-label="Page navigation example">';
			echo '<ul class="pagination">';

			if (1 < $current_page) {
				echo '<li class="page-item"><a class="page-link" href="link-trackers.php?page=' . ($current_page - 1) . '">Previous</a></li>';
			}

			echo '<li class="page-item"><a class="page-link" href="#">' . $current_page . '</a></li>';

			if ($current_page < $total_offset) {
				echo '<li class="page-item"><a class="page-link" href="link-trackers.php?page=' . ($current_page + 1) . '">Next</a></li>';
			}

			echo '</ul>';
			echo '</nav>';
		}
	}

	public function shortenLinkList()
	{
		$offset = 0;
		if (isset($_GET['page']) && !empty($_GET['page']) && is_numeric($_GET['page'])) {
			$total = $this->totalShortenLink();
			$total_offset = ceil($total / 20);

			if (($_GET['page'] - 1) < 0) {
				$offset = 0;
			}
			else if ($total_offset < ($_GET['page'] - 1)) {
				$offset = 0;
			}
			else {
				$offset = ($_GET['page'] - 1) * 20;
			}
		}

		return $this->getAll($this->shortenLinkTable, 20, $offset, 'DESC');
	}

	public function userShortenLinkList($username)
	{
		$offset = 0;
		if (isset($_GET['page']) && !empty($_GET['page']) && is_numeric($_GET['page'])) {
			$total = $this->totalUserShortenLink($username);
			$total_offset = ceil($total / 20);

			if (($_GET['page'] - 1) < 0) {
				$offset = 0;
			}
			else if ($total_offset < ($_GET['page'] - 1)) {
				$offset = 0;
			}
			else {
				$offset = ($_GET['page'] - 1) * 20;
			}
		}

		return $this->getAll($this->shortenLinkTable, 20, $offset, 'DESC', 'username', $username);
	}

	public function todayTotalClicks($shortenCode, $username)
	{
		$startTime = strtotime(strval(date('d-m-Y')) . ' 00:00:00');
		$endTime = strtotime(strval(date('d-m-Y')) . ' 23:59:59');
		$query = 'SELECT COUNT(*) FROM ' . $this->shortenLinkClicksTable . ' WHERE shorten_code = ? AND username = ? AND ' . "\n" . '        visitor_timestamp BETWEEN ' . $startTime . ' AND ' . $endTime;
		$handler = $this->getDBConnection()->prepare($query);
		$handler->bindValue(1, $this->filter($shortenCode));
		$handler->bindValue(2, $this->filter($username));
		$handler->execute();
		return $handler->fetchColumn();
	}

	public function thisMonthTotalClicks($shortenCode, $username)
	{
		$startTime = strtotime(strval(date('d-m-Y')) . ' 00:00:00');
		$endTime = strtotime(strval(date('t-m-Y')) . ' 23:59:59');
		$query = 'SELECT COUNT(*) FROM ' . $this->shortenLinkClicksTable . ' WHERE shorten_code = ? AND username = ? AND ' . "\n" . '        visitor_timestamp BETWEEN ' . $startTime . ' AND ' . $endTime;
		$handler = $this->getDBConnection()->prepare($query);
		$handler->bindValue(1, $this->filter($shortenCode));
		$handler->bindValue(2, $this->filter($username));
		$handler->execute();
		return $handler->fetchColumn();
	}

	public function getShortenCodeDetails($shortenCode)
	{
		return $this->getSingle($this->shortenLinkTable, 'shorten_code', $shortenCode);
	}

	public function trackingSystem()
	{
		if (isset($_GET['l']) && !empty($_GET['l'])) {
			$shortenCodeDetails = $this->getShortenCodeDetails($_GET['l']);

			if (empty($shortenCodeDetails)) {
				exit('Invalid tracking link... ');
			}
			else {
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

				$this->insertData($this->shortenLinkClicksTable, ['shorten_code' => $_GET['l'], 'username' => $shortenCodeDetails['username'], 'visitor_country' => $user_country, 'visitor_origin' => $visitor_origin, 'visitor_ip' => $visitor_ip, 'visitor_timestamp' => time()]);
				$this->increaseLinkClick($_GET['l']);
				header('Location: ' . $shortenCodeDetails['actual_link']);
			}
		}
		else {
			exit('Invalid tracking link...');
		}
	}

	public function updateSettings()
	{
		if (isset($_POST['free_member_limit']) && isset($_POST['paid_member_limit']) && isset($_POST['system_power']) && isset($_POST['admin_csrf_token'])) {
			if (empty($_POST['free_member_limit']) || empty($_POST['paid_member_limit']) || empty($_POST['system_power']) || empty($_POST['admin_csrf_token'])) {
				return ['success' => false, 'message' => 'All fields are required.'];
			}
			else {
				$adminController = new AdminController();

				if ($_POST['admin_csrf_token'] != $adminController->getAdminCSRFToken()) {
					return ['success' => false, 'message' => 'Invalid request.'];
				}
				else if (!is_numeric($_POST['system_power']) || (($_POST['system_power'] != 1) && ($_POST['system_power'] != 2))) {
					return ['success' => false, 'message' => 'Invalid status.'];
				}
				else if (!is_numeric($_POST['free_member_limit']) || ($_POST['free_member_limit'] < 1)) {
					return ['success' => false, 'message' => 'Invalid limit for free membership.'];
				}
				else if (!is_numeric($_POST['paid_member_limit']) || ($_POST['paid_member_limit'] < 1)) {
					return ['success' => false, 'message' => 'Invalid limit for free paid membership.'];
				}
				else {
					$this->updateData($this->shortenLinkSettingsTable, 'id', 1, ['free_member_limit' => $_POST['free_member_limit'], 'paid_member_limit' => $_POST['paid_member_limit'], 'system_power' => $_POST['system_power']]);
					return ['success' => true, 'message' => 'Settings has been updated.'];
				}
			}
		}
	}

	public function increaseLinkClick($shortenCode)
	{
		$query = 'UPDATE ' . $this->shortenLinkTable . ' SET total_visits = total_visits + 1 WHERE shorten_code = ? LIMIT 1';
		$handler = $this->getDBConnection()->prepare($query);
		$handler->bindValue(1, $this->filter($shortenCode));
		$handler->execute();
	}
}

?>