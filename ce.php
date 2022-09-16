 <?php
 define("ADAY", (60*60*24));
 if (isset($_POST['month'])) {
	 $month = $_POST['month'];
     $year = $_POST['year'];
 } else {
     $nowArray = getdate();
     $month = $nowArray['mon'];
     $year = $nowArray['year'];
 }
 $start = mktime (12, 0, 0, $month, 1, $year);
 $firstDayArray = getdate($start);
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