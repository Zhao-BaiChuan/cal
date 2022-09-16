<?php

$time = time();
//$time = date_add($timea,date_interval_create_from_date_string("1 month"));

$numDay = date('d', $time);
$numMonth = date('m', $time);
$strMonth = date('F', $time);
$numYear = date('Y', $time);
$firstDay = mktime(0,0,0,$numMonth,1,$numYear);
$daysInMonth = cal_days_in_month(0, $numMonth, $numYear);
$dayOfWeek = date('w', $firstDay);
?>
<html>
<body>
<table>
<caption><? echo($strMonth); ?></caption>
<thead>
<tr>
<th abbr="Sunday" scope="col" title="Sunday">S</th>
<th abbr="Monday" scope="col" title="Monday">M</th>
<th abbr="Tuesday" scope="col" title="Tuesday">T</th>
<th abbr="Wednesday" scope="col" title="Wednesday">W</th>
<th abbr="Thursday" scope="col" title="Thursday">H</th>
<th abbr="Friday" scope="col" title="Friday">F</th>
<th abbr="Saturday" scope="col" title="Saturday">S</th>

</tr>
</thead>
<tbody>
<tr>
<?
if(0 != $dayOfWeek) { echo('<td colspan="'.$dayOfWeek.'"> </td>'); }
for($i=1;$i<=$daysInMonth;$i++) {

if($i == $numDay) { echo('<td id="today">'); } else { echo("<td>"); }
echo($i);
echo("</td>");
if(date('w', mktime(0,0,0,$numMonth, $i, $numYear)) == 6) {
echo("</tr><tr>");
}
}
?>
</tr>
</tbody>
</table>
</body>
</html>