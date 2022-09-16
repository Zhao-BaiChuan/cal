<?php
	session_start();
	include '../funcs.php'; 
	$debug='Y';
	$whoamiv = 'calk';	// template check user
	$whoamiu = 'calcu';
	$whoami = 'Login';
	$loginlogout = './a/login.php';
	if (isset($_SESSION[$whoamiv])) {
		$whoami = $_SESSION[$whoamiv];
	}
	$eid = -1;

?>
<html>
<head>
	<link href="./style.css" type="text/css" rel="stylesheet" />
	<title>Users</title>
</head>
<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<div class='bx'>
    <div class='r'>[<a href='<?php echo $loginlogout; ?>'><?php echo $whoami; ?> ]</a>
	</div>
	</div>
<?php
	
	if ($whoami=='Login') {
		echo "Please login to continue...";
	} else {
		// login stuff
		echo "<h1>Users</h1>";
		echo showgrid($eid);
	}
?>
<a href='./a/logout.php'>Logout</a>
<hr>
<a href='./'>Home</a>
</html>
<?php

	function showgrid($editid) {
		include '../dbinfo.php';
						
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "SELECT uid, mail, lvl, llog, logfrom, tzone, emailaddr FROM calu ";
		$stmt = $conn->prepare($sql);
		
		//$stmt->bind_param('s',$usr);
		//$usrf = $usr;
		// Run the query
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			// output data of each row
			$res = "<table><tr><td>id</td><td>User</td></tr>";
			while($row = $result->fetch_assoc()) {
				$res .= "</tr><td>".$row['uid']."</td>";
				$res .= "<td>".$row['mail']."</td>";
				$res .= "<td>".$row['llog']."</td>";
				$res .= "</tr>";
			}
			$res .= "</table>";
		} 
		$conn->close();
		return $res;
	}
	

?>