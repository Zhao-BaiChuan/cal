<?php
	session_start();
	include './funcs.php';
	$whov = 'calk';
	$debug = 'Y';
	$whoami = 'Login';
	$loginlogout = './a/login.php';
	$edit = -1;
	
	
	if (isset($_SESSION[$whov])) {
		$whoami = $_SESSION[$whov];
		$loginlogout = './a/logout.php';
		if (!isset($_SESSION["security"])) {
			$_SESSION["security"] = security_lvl($whoami);
			$_SESSION["timezone"] = timezone($whoami);
		}
	} 
	$result = '';
	$ins = 0;
	if ($_SERVER['REQUEST_METHOD']=='POST') {
		if (isset($_POST['actn'])) {
			if ($_POST['actn']=='+') {
				// we are trying to do an insert bug ...
				$ins = 1;				
			} else {
				if ($_POST['actn']=='SaveBug') {
					$fbug = test_input($_POST["fbug"]);
					$furl = test_input($_POST['furl']);
					$fusr = $_SESSION['calcu'];
					$result = insert_bug($furl, $fusr, $fbug);
				} else if ($_POST['actn']=='UpdateBug') {
					$url = test_input($_POST['edurl']);
					$bug = test_input($_POST['edbug']);
					$bid = test_input($_POST['bugid']);
					// 	function update_bug($bugid, $furl, $fbug) 
					$result = update_bug($bid, $url, $bug);						
					$edit = -1;
				}
			}
		} else {
			if (isset($_POST['xid'])) {
				$edit = test_input($_POST['xid']);
			}
		}
	}
			

?><html>
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
<h2>Bugs - What's broken ...</h2>
<?php
	if (!isset($_SESSION[$whov])) {
		echo 'Please login above</br>';
	} else {
		// logged in properly
		echo '<p>My first paragraph.</p>';
		if ($ins==1) {
			echo show_newbugform();
		} else {
			echo show_bugs($edit);
		}
	}
	
	
?>


<?php
	if ((isset($debug))&&($debug=='Y')) {
		echo show_aa('SESSION', $_SESSION);
		echo show_aa('post',$_POST);
		echo show('debug', $debug);
		echo show('result', $result);
	}
	
?>
<hr>
<a href='./'>Home</a>
</form>
</body>
</html>
<?php

	function show_bugs($edit) {
		// show the bugs in table cal_bugs
		
		$res = "<table><tr><td>Bugs table</td></tr></table>";
		include '../dbinfo.php';
						
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$answer = "";
		// database name ...
		// $sql = "SELECT uid FROM calu WHERE mail = '$usr' ";
		$sql = "SELECT `bid`, `burl`, `bcrdt`, `bcru`, calu.mail, `bug`, `status`, brdt FROM `cal_bugs` LEFT JOIN calu ON cal_bugs.bcru = calu.uid WHERE 1 ";
		// Run the query
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			// output data of each row
			$res = "<input type='submit' name='actn' value='+'><table><tr>".td('id').td('URL').td('created');
			$res .= td('User').td('bug').td('status').td('Updated').td('')."</tr>";
			while($row = $result->fetch_assoc()) {
				if ($edit==$row['bid']) {
					// show edit presentation
					$burl = $row['burl'];
					$res .= "<tr>".td($row["bid"]).td("<input type='text' name='edurl' value='".$burl."' size='80' maxlength='255'>");
					$bugid = $row['bid'];
					$res .= td($row['bcrdt']).td($row['mail']."<input type='hidden' name='bugid' value='".$bugid."'>");
					$bbug = trim($row['bug']);
					$res .= td("<textarea name='edbug' id='edbug' rows='8' cols='70'>".$bbug."</textarea>").td(bug_status($row['status']));
					$res .= td($row['brdt']);
					$res .= "<td><input type='submit' name='actn' value='UpdateBug'></td></tr>";
				} else {
					//show default presentation of grid
					$res .= "<tr>".td($row["bid"]).td($row['burl']);
					$res .= td($row['bcrdt']).td($row['mail']);
					$res .= td(substr($row['bug'],0,10)."...");
					$res .= td(bug_status($row['status']));
					$res .= td($row['brdt']);
					$res .= "<td><input type='submit' name='xid' value='".$row['bid']."'></td></tr>";
				}
			}
			$res .= "<tr><td colspan='6'>".$result->num_rows." rows, edit=".$edit."</td></tr>";
			$res .= "</table>";
		} 
		$conn->close();
		
		return $res;
	}
	
	function show_newbugform() {
		$res = "<table><tr>".th('url - where bug is - cannot fix if cannot find').th('Bug description')."</tr>";
		$res .= "</tr>".td("<input type='text' name='furl'>").td("<input type='text' name='fbug'>")."</tr>";
		$res .= "</tr>".td('&nbsp;').td("<input type='submit' name='actn' value='SaveBug'>")."</tr></table>";
		return $res;
	}
		
		

	// Insert new bug
	function insert_bug($furl, $fusr, $fbug) {
		// INSERT INTO cal_bugs (burl, bcru, bug) 
		// VALUES ('https://www.toop.ca/cal/index.php', 2,'e-mail not working. Cannot send list of who has birthday event')
		include '../dbinfo.php';
			
		if (3<5) {
			try {
				$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
				// set the PDO error mode to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				//$status = "REQUEST";
					
				//$sql = "INSERT INTO `acs_slots`(`id`, `dpt`, `slot4`, `slot5`) VALUES ([value-1],[value-2],[value-3],[value-4])";
				$sql = "INSERT INTO cal_bugs (burl, bcru, bug) ";
				$sql .= "VALUES (:burl, :busr, :bug )";
					
				// prepare sql and bind parameters
				//$sql = "UPDATE acsftnidm SET ftnu=:ftnu, fname=:fname,";
				//$sql .= "fphone=:fphone, faddr=:faddr, fpost=:fpost, fupdt=:fupdt WHERE ffid=:ffid";
				//$sql = "INSERT INTO acsC_dob (fid, nam, dob) ";
				//$sql .= "VALUES (:ffid, :fnam, :fdob) ";
				$stmt = $conn->prepare($sql);
					
				$stmt->bindParam(':burl', $furl);
				$stmt->bindParam(':busr', $fusr);
				$stmt->bindParam(':bug', $fbug);
				
				// update a row
				// $trip = $tripName;
				$stmt->execute();

				$res = "bug inserted " . $bug;
			}
			catch(PDOException $e) {
				$res = "Error: " . $e->getMessage();
			}
			$conn = null;
		}
		return $res;
	}
	
	// Update bug
	function update_bug($bugid, $furl, $fbug) {
		// INSERT INTO cal_bugs (burl, bcru, bug) 
		// VALUES ('https://www.toop.ca/cal/index.php', 2,'e-mail not working. Cannot send list of who has birthday event')
		include '../dbinfo.php';
			
		if (3<5) {
			try {
				$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
				// set the PDO error mode to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				//$status = "REQUEST";
					
				//$sql = "INSERT INTO `acs_slots`(`id`, `dpt`, `slot4`, `slot5`) VALUES ([value-1],[value-2],[value-3],[value-4])";
				$sql = "UPDATE cal_bugs SET burl=:furl , bug=:fbug , brdt = :fbrdt , status = :fstatus  WHERE bid=:fbid ";
							
				// prepare sql and bind parameters
				//$sql = "UPDATE acsftnidm SET ftnu=:ftnu, fname=:fname,";
				//$sql .= "fphone=:fphone, faddr=:faddr, fpost=:fpost, fupdt=:fupdt WHERE ffid=:ffid";
				//$sql = "INSERT INTO acsC_dob (fid, nam, dob) ";
				//$sql .= "VALUES (:ffid, :fnam, :fdob) ";
				$stmt = $conn->prepare($sql);
					
				$stmt->bindParam(':furl', $furl);
				$stmt->bindParam(':fbug', $fbug);
				$stmt->bindParam(':fbid', $bugid);
				$dt2=date("Y-m-d H:i:s");
				$stmt->bindParam(':fbrdt', $dt2);
				$nstatus = 1;
				$stmt->bindParam(':fstatus', $nstatus);
				
				// update a row
				// $trip = $tripName;
				$stmt->execute();

				$res = "bug " . $bugid." updated";
			}
			catch(PDOException $e) {
				$res = "Error: " . $e->getMessage();
			}
			$conn = null;
		}
		return $res;
	}
	// the password that was found for user $usr
	function uid($usr) {
		include '../../dbinfo.php';
						
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$answer = "";
		// database name ...
		$sql = "SELECT uid FROM calu WHERE mail = '$usr' ";
		//$_SESSION["sql"] = $sql;
		// Run the query
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				$answer = $row["uid"];
			}
		} 
		$conn->close();
		return $answer;
	}
	
	function record_login($uid, $from) {
		//$sql = "UPDATE templu SET llog=CURRENT_TIMESTAMP(), logfrom=? WHERE mail=? ";
		include '../../dbinfo.php';
						
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		//$sql = "UPDATE SELECT pw FROM templu WHERE mail = ? ";
		//$sql = "UPDATE calu SET llog=CURRENT_TIMESTAMP(), logfrom=? WHERE uid=? ";
		try {
			$sql = "UPDATE calu SET llog=CURRENT_TIMESTAMP(), logfrom=? WHERE uid=? ";
			$stmt = $conn->prepare($sql);
			
			$stmt->bind_param('si',$from, $uid);
			//$usrf = $usr;
			// Run the query
			$stmt->execute();
			//$result = $stmt->get_result();
			$answer = 'ok';
		} catch(Exception $e) {
			$answer = 'Error '.$e->getMessage();
		}

		$conn->close();
		return $answer;
	}
	
	function bug_status($st) {
		if ($st==0) {
			$res = 'Outstanding';
		} else if ($st==1) {
			$res = 'under development';
		} else if ($st==2) {
			$res = 'Resolved';
		} else {
			$res = 'Unknown Status';
		}
		
		return $res;
	}

?>	