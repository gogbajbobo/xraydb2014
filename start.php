<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Xray2014DB Start</title>
	<style type="text/css">
		table {border-style:solid; border-width:thin; margin: 1em 0;}
		th, td {padding: 0 1em; text-align: left;}
		select {margin: 1em;}
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

// Form start
echo '<form action="sql.php?" method="get">';

// Participant

echo '<label for="participant_id">Select participant:</label><br />';
$result=mysql_query('SELECT id, first_name, middle_name, last_name FROM participants ORDER BY id DESC');
echo '<select name="participant_id">';
while ($row=mysql_fetch_assoc($result)) {
	$name=$row['last_name'] . ' ' . $row['first_name'] . ' ' . $row['middle_name'];
	echo '<option value="' . $row['id'] . '">' . $name . "</option>";
}
echo '</select>';
echo '<a href="add.php?table=participants">Add new</a><br/>';

// Paper

echo '<label for="paper_id">Select paper:</label><br />';
$result=mysql_query('SELECT id, title FROM papers ORDER BY id DESC');
echo '<select name="paper_id">';
while ($row=mysql_fetch_assoc($result)) {
	echo '<option value="' . $row['id'] . '">' . $row['title'] . "</option>";
}
echo '</select>';
echo '<a href="add.php?table=papers">Add new</a><br/>';
echo '<input type="hidden" name="table" value="pp" />';
echo '<input type="hidden" name="action" value="add" />';
echo '<br /><input type="submit" value="link" /><br />';

//<input type="hidden" name="id" value="' . $_GET['id'] . '" />
//<input type="hidden" name="table" value="' . $table . '" />
//<input type="hidden" name="action" value="' . $action . '" />
//<input type="submit" value="' . $action . '" /><br /></form>';

// Form end
echo '</form>';


echo '<br /><a href="show.php">show.php</a>';



?>
</body>
</html>
