<?php
	// funcs.php
	
	function test_input($data) {
		$res = htmlentities($data, ENT_QUOTES, 'UTF-8');
		return $res;
	}
	
	function show_aa($name, $arr) {
		$membc = 0;
		$tres = '';
		foreach ($arr as $key=>$value) {
			$membc++; //$tres .= "<tr><td>".$key."</td><td>".$value."</td></tr>\r";
		}
		if ($membc==0) {
			$tres = $name . " is empty</br>";
		} else {
			$tres = "<table><tr><th>".$name."</th><th>&nbsp;</th></tr>\r";
			//$tres .= "<caption>Debug info</caption>";
			foreach ($arr as $key=>$value) {
				$tres .= "<tr><td>".$key."</td><td>".$value."</td></tr>\r";
			}
			$tres .= "</table>";
		}
		
		return $tres;
	}
	
	function show($n, $v) {
		$res = "<table><tr><td>Name</td><td>Value</td></tr>";
		$res .= "<tr><td>".$n."</td><td>".$v."</td></tr></table></br>";
		return $res;
	}
	
	// Returns the security level
	function security_lvl($who) {
		$lvl = 4;													// not authorized user 
		include '../dbinfo.php';
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {									// Check connection
		  die("Connection failed: " . $conn->connect_error);		// show error if any
		}

		$sql = "SELECT lvl FROM calu WHERE Mail=? ";
		$stmt = $conn->prepare($sql);
		
		$stmt->bind_param('s',$who);
		//$usrf = $usr;
		// Run the query
		$stmt->execute();
		$result = $stmt->get_result();
		//$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
			$lvl = $row["lvl"];			// result
		  }
		} else {
		  $lvl = 5; #"0 results";	// result
		}
		$conn->close();
		return $lvl;
	}
	
		// Returns the security level
	function timezone($who) {
		$lvl = 4;													// not authorized user 
		include '../dbinfo.php';
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {									// Check connection
		  die("Connection failed: " . $conn->connect_error);		// show error if any
		}

		$sql = "SELECT tzone FROM calu WHERE Mail=? ";
		$stmt = $conn->prepare($sql);
		
		$stmt->bind_param('s',$who);
		//$usrf = $usr;
		// Run the query
		$stmt->execute();
		$result = $stmt->get_result();
		//$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
			$res = $row["tzone"];			// result
		  }
		} 
		$conn->close();
		return $res;
	}
	
	function td($stuff) {
		return "<td>".$stuff."</td>";
	}
	
	function ti($stuff) {
		return "<td style='background-color:#00FF00'>".$stuff."</td>";
	}

	
	function th($stuff) {
		return "<th>".$stuff."</th>";
	}
?>