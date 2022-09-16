<?php
	session_start();
	include './funcs.php';
	$whov = 'calk';
	$debug = 'Y';
	$whoami = 'Login';
	$loginlogout = './a/login.php';
	
	
	if (isset($_SESSION[$whov])) {
		$whoami = $_SESSION[$whov];
		$loginlogout = './a/logout.php';
		if (!isset($_SESSION["security"])) {
			$_SESSION["security"] = security_lvl($whoami);
			$_SESSION["timezone"] = timezone($whoami);
		}
	} 

?><html><!DOCTYPE html>
<html>
<head>
	<link href="./style.css" type="text/css" rel="stylesheet" />
	<title>Calendar</title>
</head>
<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<div class='bx'>
    <div class='r'>[<a href='<?php echo $loginlogout; ?>'><?php echo $whoami; ?> ]</a></br>
	<?php if ($whoami!='Login') { echo "<a href='usrs.php'>User Profile</a>";} ?>
	</div>
	</div>
<h1>Events</h1>
<?php
	if (!isset($_SESSION[$whov])) {
		echo 'Please login above</br>';
	} else {
		// logged in properly
		echo '<p>My first paragraph.</p>';
		$today = date("Y-m-d H-i");
		echo 'today is ' . $today.'</br>';
		date_default_timezone_set($_SESSION["timezone"]);
		
		echo 'today is ' . $today;
	}
	
?>
</hr>
<a href='./'>Home</a>

<?php
	if ((isset($debug))&&($debug=='Y')) {
		echo show_aa('SESSION', $_SESSION);
		echo show('debug', $debug);
	}
	
?>
<hr>
<a href='./'>Home</a>
</form>
</body>
</html>
<?php

?>	