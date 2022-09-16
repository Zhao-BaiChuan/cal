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
	if (isset($_SESSION['security'])) {
		$lvl = $_SESSION['security'];
	} else {
		$lvl = 5;
	}
	if ($_SERVER['REQUEST_METHOD']=='POST') {
		$_SESSION['smo'] = test_input($_POST['cmos']);
	} else {
		if (!isset($_SESSION['smo'])) {
			$_SESSION['smo'] = date('n');
		}
	}
		$year = date("Y");
		$mon = date("m");

		/* query 
		
			SELECT
				`id`,
				`yr`,
				`mm`,
				`dd`,
				`fname`,
				`lname`,
				`evtype`,
				`span`,
				`num`
			FROM
				`caldates`
			WHERE
				id NOT IN(
				SELECT
					evid
				FROM
					cal_excludes
				WHERE
					cal_excludes.usr = 2
			) AND mm = 11
			
*/
	
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
		$today = date("Y-m-d H-i");
		echo 'Today is ' . $today. " GMT";
		//date_default_timezone_set($_SESSION["timezone"]);
		
		//echo 'today is ' . $today;
		//echo "Happy ".mon($_SESSION['smo']);
		echo "</br>View ".ddl_mos($_SESSION["smo"])." <input type='submit' value='Refresh' ></br>";
		echo show_ev($_SESSION['smo']);
	}
	
?>


<?php
		
	if ((isset($debug))&&($debug=='Y')&&($lvl<3)) {
		echo show_aa('SESSION', $_SESSION);
		echo show('debug', $debug);
		echo show('year', $year);
		echo show('mon', $mon);
		echo show_aa("post", $_POST);
	}
	
?>
<hr>
<a href='./'>Home</a>
</form>
</body>
</html><?php

	function ddl_mos($mm) {
		// https://www.w3schools.com/php/phptryit.asp?filename=tryphp_array_multi
		$sorts = array(
		array("1", "Jan"),
		array("2","Feb"),
		array("3","Mar"),
		array("4","Apr"),
		array("5","May"),
		array("6","Jun"),
		array("7","Jul"),
		array("8","Aug"),
		array("9","Sep"),
		array("10","Oct"),
		array("11","Nov"),
		array("12","Dec"),
		);
		$res = "<select name='cmos' id='mm'>\r\n";
		for ($i=0; $i<sizeof($sorts);$i++) {
			if ($sorts[$i][0]==$mm) {
				$res .= "<option selected value='" . $sorts[$i][0] . "'>" . $sorts[$i][1] . "</option>\r\n";
			} else {
				$res .= "<option value='" . $sorts[$i][0] . "'>" . $sorts[$i][1] . "</option>\r\n";
			}
		}
		$res .= "</select>\r\n";
					
		return $res;
	}

	function show_ev($mo) {
		include '../dbinfo.php';
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {									// Check connection
		  die("Connection failed: " . $conn->connect_error);		// show error if any
		}
		$tyear = date("Y");
		$sql = "SELECT id, yr, mm, dd, fname, lname, evtype, span, num FROM caldates WHERE mm = ? ";
		$stmt = $conn->prepare($sql);
		
		$stmt->bind_param('s',$mo);
		//$usrf = $usr;
		// Run the query
		$stmt->execute();
		$result = $stmt->get_result();
		//$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  // output data of each row
		  $res = "<table>".th('Year').th('Date').th('Person/(s)').th('Event').th('')."</tr>";
		  while($row = $result->fetch_assoc()) {
			  $yy = $row['yr'];
			  $yrs = $tyear - $yy;
			  $res .= "<tr>".td($row['yr']).td(mon($row['mm'])." ".$row['dd']).td($row['fname']." ".$row['lname']);
			  $span = $row['span'];
			  if (strlen($span)>2) {
				  $res .= td($row['evtype']." ".$row['span'])."</tr>";
			  } else {
				  $res .= td(st($yrs)." ".$row['evtype'])."</tr>";
			  }
		  }
		} 
		$conn->close();
		return $res;
	}
	
	function st($num) {
		if ($num == 1) {
			$res = $num."st";
		} else {
			$res = $num."th";
		}
		return $res;
	}
	
	function mon($m) {
		switch ($m) {
			case 1 : 
			$res = 'January';
			break;
			case 2 :
			$res = 'February';
			break;
			case 3 :
			$res = 'March';
			break;
			case 4 :
			$res = 'April';
			break;
			case 5 :
			$res = 'May';
			break;
			case 6 :
			$res = 'June';
			break;
			case 7 :
			$res = 'July';
			break;
			case 8 :
			$res = 'August';
			break;
			case 9 :
			$res = 'September';
			break;
			case 10 :
			$res = 'October';
			break;
			case 11 :
			$res = 'November';
			break;
			case 12 :
			$res = 'December';
			break;
		}
		return $res;
	}
	
		
?>