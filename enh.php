<?php
	session_start();
	include './funcs.php';
	$whov = 'calk';
	$debug = 'N';
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
	if ($_SERVER['REQUEST_METHOD']=='POST') {
		$_SESSION['smo'] = test_input($_POST['cmos']);
	} else {
		if (!isset($_SESSION['smo'])) {
			$_SESSION['smo'] = date('n');
		}
	}

?>
<!DOCTYPE html>
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
<?php
	if (!isset($_SESSION[$whov])) {
		echo 'Please login above</br>';
	} else {
		echo 'Thanks for login';
	}


?>
<hr>
<a href='./'>Home</a>
</form>
</body>
</html>
<?php


?>