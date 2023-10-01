<?php
// show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// require auth0 class
require_once('class.auth0.php');

// start auth0 autoload
$auth = new auth0Handler();

// CALLBACK
if(isset($_GET['code']) && isset($_GET['state'])) {
	$auth->handleCallback();
}

// LOGOUT
elseif(isset($_GET['logout'])) {
	$auth->logout();
}

// LOGIN
elseif(isset($_GET['login'])) {
	$auth->login();
}

// SHOW CURRENT LOGIN STATUS
else {
	if($auth->isLoggedin()) {
		// get user details
		$userDetails = $auth->getUserDetails();

		// output
		echo 'You are logged in.<br/><br/>';
		echo 'Name: '.$userDetails['name'].'<br/>';
		echo 'Logged in @ '.date('d.m.Y H:i:s', $userDetails['iat']).'<br/>';
		echo 'Expires @ '.date('d.m.Y H:i:s', $userDetails['exp']).'<br/><br/>';
		echo '<button onclick="window.location.href='."'".$auth->getConfig()['appBaseUrl']."'".'">appBaseUrl</button><br/><br/>';
		echo '<button onclick="window.location.href='."'".$auth->getConfig()['logoutUrl']."'".'">Logout</button><br/>';
		echo '<pre>';
		var_dump($userDetails);
		echo '</pre>';
	}
	else {
		// output 
		echo 'Unauthorized.<br/><br/>';
		echo '<button onclick="window.location.href='."'".$auth->getConfig()['loginUrl']."'".'">Login</button>';
	}
}