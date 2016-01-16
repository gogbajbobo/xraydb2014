<html lang="en">
<head>
	<title>Xray2014DB Show</title>
	<style type="text/css">
		table {border-style:solid; border-width:thin; margin: 1em 0; border-collapse: collapse;}
		th, td {padding: .5em 1em; text-align: left; border-top: solid black thin;}
	</style>
</head>
<body>
<?php

$host='localhost';
$database='xray2014';
$dbuser='xraydb';
mysql_connect($host,$dbuser) or die(mysql_error());
mysql_query('SET NAMES utf8');
mysql_select_db($database) or die(mysql_error());

echo '<a href="start.php">start.php</a><br /><br />';
echo '<a href="index.php">View</a><br /><br />';

if (isset($_GET['ShowTable'])) {
	$table=$_GET['ShowTable'];
	echo 'ShowTable = ' . $table . '<br /><br />';
	$result=mysql_query('show fields from ' . $table);
	echo '	<form action="add.php?" method="get">
				<input type="hidden" name="table" value="' . $table . '" />
				<input type="submit" value="Add" /><br />
			</form>';
	echo '<form action="?" method="get"><input type="submit" value="Back" /><br /></form>';
	echo '<table>';
	echo '<tr>';
	while ($columns=mysql_fetch_array($result)) {
		echo '<th>';
		echo $columns[0];
		echo '</th>';
	}
	echo '<th></th>';
	echo '</tr>';
	$result=mysql_query('select * from ' . $table);
	while ($row=mysql_fetch_assoc($result)) {
		echo '<tr>';
		foreach ($row as $cell) {
			echo '<td>' . $cell . '</td>';
		}
		echo '	<td>
					<form action="sql.php?" method="get">
						<input type="hidden" name="action" value="del" />
						<input type="hidden" name="id" value="' . $row['id'] . '" />
						<input type="hidden" name="table" value="' . $table . '" />
						<input type="submit" value="Delete ' . $row['id'] . '" />
						<br />
					</form>
					<form action="add.php?" method="get">
						<input type="hidden" name="action" value="modify" />
						<input type="hidden" name="id" value="' . $row['id'] . '" />
						<input type="hidden" name="table" value="' . $table . '" />
						<input type="submit" value="Modify ' . $row['id'] . '" />
						<br />
					</form>				</td>';
		echo '</tr>';
	}
	echo '</table>';
}
else {
	$result=mysql_query('show tables from ' . $database);
	while ($tables=mysql_fetch_assoc($result)) {
		foreach ($tables as $table) {
			echo '<form action="?" method="get">';
			echo '<input type="hidden" name="ShowTable" value="' . $table . '" />';
			echo '<input type="submit" value="' . $table . '" /><br /></form>';
		}
	}
}


?>
</body>
</html>
