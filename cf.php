 <?php
 

 ?>
 <html>
 <head>
 <link href="./style.css" type="text/css" rel="stylesheet" />
 <title><?php print "Calendar:
     ".$firstDayArray['month']." ".$firstDayArray['year'] ?></title>
 <head>
 <body>
 <form method="post" action="<?php print "$_SERVER[PHP_SELF]"; ?>">
 <select name="month">
 <?php
 
  define("ADAY", (60*60*24));
  echo ddl_mos($_SESSION['month']);
  echo " ";
  echo ddl_yr($_SESSION['year']);
 
 }
 $start = mktime (12, 0, 0, $month, 1, $year);
 $firstDayArray = getdate($start);
 ?>
 <?php
 $months = Array("January", "February", "March", "April", "May",
 "June", "July", "August", "September", "October", "November", "December");
 
 for ($x=1; $x <= count($months); $x++) {
     print "\t<option value=\"$x\"";
     print ($x == $month)?" SELECTED":"";
     print ">".$months[$x-1]."\n";
 }
 ?>
 </select>
 <select name="year">
 <?php
 for ($x=1990; $x<2030; $x++) {
     print "\t<option";
     print ($x == $year)?" SELECTED":"";
     print ">$x\n";
 }
 ?>
 </select>
 <input type="submit" value="Go!">
 </form>
 <br>
 <?php
 $days = Array("Sunday", "Monday", "Tuesday", "Wednesday",
 "Thursday", "Friday", "Saturday");
 
 print "<TABLE BORDER = 1 CELLPADDING=5>\n";
 foreach ($days as $day) {
     print "\t<td><b>$day</b></td>\n";
 }
 for ($count=0; $count < (6*7); $count++) {
     $dayArray = getdate($start);
     if (($count % 7) == 0) {
         if ($dayArray['mon'] != $month) {
             break;
         } else {
             print "</tr><tr>\n";
         }
     }
     if ($count < $firstDayArray['wday'] || $dayArray['mon'] != $month) {
         print "\t<td><br></td>\n";
     } else {
         print "\t<td>".$dayArray['mday']." &nbsp;&nbsp; </td>\n";
         $start += ADAY;
     }
 }
 print "</tr></table>";
 ?>
 <hr>
 <a href='./'>Home</a>
 </body>
 </html>
 <?php
 
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
		array("12","Dec")
		);
		$res = "<select name='month' id='mm'>\r\n";
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

 function ddl_yr($yy) {
		// https://www.w3schools.com/php/phptryit.asp?filename=tryphp_array_multi
		$sorts = array(
		array("2019", "2019"),
		array("2020", "2020"),
		array("2021", "2021"),
		array("2022", "2022"),
		array("2023", "2023")
		);
		$res = "<select name='year' id='yy'>\r\n";
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
 ?>