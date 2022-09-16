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
	<style>
table.r, th.r, td.r {
  border: 1px solid black;
  border-collapse: collapse;
  text-align: left;
}
</style>
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
		echo 'Search ';
		echo show_search();
	} 

?>
<hr>
<a href='./'>Home</a>
</form>
</body>
</html>
<?php

	function show_search() {
		$res = "<table class='r'><tr><td class='r'>Last</td><td class='r'>First</td><td></td></tr>";
		$res .= "<tr><td class='r'><input type='text' name='flast'></td>";
		$res .= "<td class='r'><input type='text' name='ffirst'></td><td class='r'></td>";
		$res .= "<td class='r'><input type='submit' value='Search'></td></tr>";
		$res .= "<tr><td class='r'>three</td></tr>";
		$res .= "</table>";
		$res .= "Another";
		return $res;
	}

?>