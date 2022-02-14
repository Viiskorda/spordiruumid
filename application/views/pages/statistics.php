Statistika

<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>

<table width="1200" style="margin: 0 auto;" border="0" cellspacing="5" cellpadding="5">
	<tr style="background:#CCC">
		<th>Sr No</th>
		<th>allRooms</th>
		<th>buildingID</th>
		<th>roomID</th>
		<th>userID</th>
		<th>user Role ID</th>
		<th>timestamp</th>
		<th>userIP</th>
		<th>Seade</th>
	</tr>
	<?php
	$i = 1;
	foreach ($statistics_data as $row) {
		echo "<tr>";
		echo "<td>" . $i . "</td>";
		echo "<td>" . $row->allRooms  . "</td>";
		echo "<td>" . $row->buildingID  . "</td>";
		echo "<td>" . $row->roomID . "</td>";
		echo "<td>" . $row->userID . "</td>";
		echo "<td>" . $row->userRoleID  . "</td>";
		echo "<td>" . $row->timestamp  . "</td>";
		echo "<td>" . $row->userIP . "</td>";
		echo "<td>" . $row->userAgent . "</td>";
		echo "</tr>";
		$i++;
	}
	?>
</table>
