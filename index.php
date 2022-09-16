<?php
	session_start();
	include './funcs.php';
	$whov = 'calk';
	$debug = 'Y';
	$whoami = 'Login';
	$loginlogout = './a/login.php';
	$lvl='';
	if (isset($_SESSION['security'])) {
		$lvl = $_SESSION['security'];
	} else {
		$lvl = 5;
	}
	$events=array('-20');
	
	if (isset($_SESSION[$whov])) {
		$whoami = $_SESSION[$whov];
		$loginlogout = './a/logout.php';
		if (!isset($_SESSION["security"])) {
			$_SESSION["security"] = security_lvl($whoami);
			$_SESSION["timezone"] = timezone($whoami);
		}
	} 
	$today = date("Y-m-d H-i");
	$y = date("Y");
	$n = date("n");
	
	if ($_SERVER['REQUEST_METHOD']=='POST') {
		if (isset($_POST['cmos'])) {
			$_SESSION['smo'] = test_input($_POST['cmos']);
			$_SESSION['syr'] = test_input($_POST['cyear']);
		}
	} else {
		$_SESSION['syr'] = $y;
	}

?><!DOCTYPE html>
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
		// logged in properly
		//echo '<p>My first paragraph.</p>';
		echo 'Today is ' . $today. " GMT";
		//date_default_timezone_set($_SESSION["timezone"]);
		
		//echo "Happy ".mon($_SESSION['smo']);
		echo ddl_year($_SESSION['syr']).' ';
		if (isset($_SESSION['smo'])) {
			echo ddl_mos($_SESSION["smo"])." <input type='submit' value='Refresh' ></br>";
		} else {
			$_SESSION['smo'] = $n;
			echo ddl_mos($_SESSION["smo"])." <input type='submit' value='Refresh' ></br>";
		}
		$tempres = show_ev($_SESSION['smo']);
		echo show_month($_SESSION['syr'], $_SESSION['smo']);
		//echo $tempres;
		echo show_ev($_SESSION['smo']);
		

		if ((isset($debug))&&($debug=='Y')&&($lvl<3)) {
			echo show_aa('SESSION', $_SESSION);
			echo show('debug', $debug);
			echo show('y', $y);
			echo show('n', $n);
			echo show('lvl', $lvl);
			echo show_aa("post", $_POST);
			print_r($events);
		}

	}
?>
<hr>
<a href='./'>Home</a>&nbsp; <a href='./ce2.php'>Development</a>&nbsp; <a href='./bugs.php'>Bugs</a>&nbsp;<a href='./srch.php'>Search</a>
</form>
</html>
<?php
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
	

	function show_ev($mo) {
		include '../dbinfo.php';
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {									// Check connection
		  die("Connection failed: " . $conn->connect_error);		// show error if any
		}
		$tyear = date("Y");
		$sql = "SELECT id, yr, mm, dd, fname, lname, evtype, span, num FROM caldates WHERE mm = ? ";
		$stmt = $conn->prepare($sql);
		
		if (isset($GLOBALS['events'])) {
			//unset($GLOBALS['events']);
		} 
		
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
			  
			  //$temp = $row['fname']." ".$row['lname']."  ";
			  
			  $span = $row['span'];
			  if (strlen($span)>2) {
				  $res .= td($row['evtype']." ".$row['span'])."</tr>";
				  
					//$temp .= $row['evtype']." ".$row['span'];
				  
			  } else {
				  $res .= td(st($yrs)." ".$row['evtype'])."</tr>";
				  
					//$temp .= $row[st($yrs)." ".$row['evtype'];
			  }
			  if (isset($GLOBALS['events'])) {
				  array_push($GLOBALS['events'], $row['dd']);
			  } else {
				  $GLOBALS['events'] = array($row['dd']);
			  }
		  }
		  $res .= '</table>';
		} 
		$conn->close();
		return $res;
	}
	
	function show_month($yr,$mo) {
		//echo "Oct 3,1975 was on a ".date("l", mktime(0,0,0,10,3,1975))
		$dim = date('t', mktime(0,0,0,$mo,1,$yr));
		$firstday = date('N', mktime(0,0,0,$mo,1,$yr));
		$tyear = date("Y");
		$tmon = date("n");
		$tday = date("d");
		$res = "<table class='nob'>";
		$days = Array("Sunday", "Monday", "Tuesday", "Wednesday",
		 "Thursday", "Friday", "Saturday");
		$res .= "<tr class='nob'>";
		// print days of the week
		for ($i=0;$i<count($days);$i++) {
			$res .= tdb($days[$i]);
		}
		// print the days
		$res .= "</tr>";
		$off= -7;
		$ccc=0;
		$dom = $ccc-$firstday;
		for ($i=-1;$i<6;$i++) {
			$res .= "<tr class='nob'>";
			for ($d=0;$d<count($days);$d++) {
				$dom++;
				$ccc++;
				$star = '';
				if (in_array($dom,$GLOBALS['events'])) {
					$star = '*';
				}
					
				if (($yr==$tyear)&&($mo==$tmon)&&($tday==$dom)) {
					$res .= tdi(dis($dom,$dim).$star);
				} else {
					$res .= tdb(dis($dom,$dim).$star);
				}
			}
			if ($dom>$dim) {
				break;
			}
			$res .= "</tr>";
		}
		$res .= "</tr>";
		//$res .= "<tr><td colspan=7>".$yr." ".$mo." f=".$firstday." o=".$dim."</tr>";
		$res .= "</table>";
		
		return $res;
	}
	function dis($num, $max) {
		$res = '';
		if ($num>0) {
			if ($num<=$max) {
				$res = $num;
			}
		}
		return $res;
	}
	
	
	function disi($num, $max) {
		$res = '';
		if ($num>0) {
			if ($num<=$max) {
				$res = $num;
			}
		}
		return $res;
	}

	function ddl_year($yy) {
		// https://www.w3schools.com/php/phptryit.asp?filename=tryphp_array_multi
		if (isset($yy)) {
			$syear = $yy;
		} else {
			$syear = date('n');
		}		
		$sorts = array(			
			array($syear, $syear),
			array($syear+1, $syear+1),
			array($syear+2, $syear+2),
			array($syear+3, $syear+3),
			array($syear+4, $syear+4)
		);
		$res = "<select name='cyear' id='yy'>\r\n";
		for ($i=0; $i<sizeof($sorts);$i++) {
			if ($sorts[$i][0]==$yy) {
				$res .= "<option selected value='" . $sorts[$i][0] . "'>" . $sorts[$i][1] . "</option>\r\n";
			} else {
				$res .= "<option value='" . $sorts[$i][0] . "'>" . $sorts[$i][1] . "</option>\r\n";
			}
		}
		$res .= "</select>\r\n";
					
		return $res;
	}
	
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
	
	function tdb($stuff) {
		return "<td>".$stuff."</td>\r\n";
	}
	
	function tdi($stuff) {
		return "<td class='imp'>".$stuff."</td>\r\n";
	}
	

	
	function thb($stuff) {
		return "<th>".$stuff."</th>";
	}
?>