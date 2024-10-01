<?php
	namespace Emailqueue;
	$title = "Email Queue";
	
	// $currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	// if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
	// 	http_response_code(404);
	// 	die("");
	// }

	include_once dirname(__FILE__)."/common.inc.php";
	if ($utils->getglobal("aa") != "view_iframe_body") {
		require_once "themes/default/incs/header.theme.php";
	}
	
	$a = $utils->getglobal("a");
	if (!$a || $a == "")
		$a = "home";
	switch ($a) {
		case "home":
			include_once dirname(__FILE__)."/classes/home.class.php";
			$home = new home();
			$output->add($home->getinfo());
			break;
		
		case "manager":
            include_once dirname(__FILE__)."/classes/manager.class.php";
            $manager = new manager();
            $output->add($manager->run());
            break;

		case "servicetools":
			include_once dirname(__FILE__)."/classes/servicetools.class.php";
			// $servicetools = new servicetools();
			$output->add($servicetools->run());
			break;
	}
	
	// Control cases wich don't need head nor footer
	if ($utils->getglobal("aa") != "view_iframe_body") {
	   $output->add_tobeggining($html->head());
	//    $output->add($html->foot());
	}
	
	echo $output->dump()."</div>";
	if ($utils->getglobal("aa") != "view_iframe_body") {
    	require_once "themes/default/incs/footer.theme.php";
	}
	
	$db->disconnect();

?>
