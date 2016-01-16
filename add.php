<html lang="en">
<head>
	<title>Xray2014DB Add</title>
	<style type="text/css">
		input, textarea, select {width: 25em;}
		input, textarea, select {margin-bottom: 1em; margin-right: 1em;}
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

if (isset($_GET['table'])) {
	$table=$_GET['table'];
	$action='add';
	echo 'ShowTable = ' . $table . '<br /><br />';

	if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action']=='modify') {
		$result=mysql_query('select * from ' . $table . ' where id=' . $_GET['id']);
		$row_values=mysql_fetch_assoc($result);
		$action='modify';
	}

	$result=mysql_query('show fields from ' . $table);
	echo '<form action="sql.php?" method="get">';
	while ($columns=mysql_fetch_array($result)) {
		if ($columns[0]!='id'&&$columns[0]!='organization_id') {
			echo '<label for="' . $columns[0] . '">' . $columns[0] . '</label><br />';
			if (substr_count($columns[1],'enum')>0) {
				echo '<select name="' . $columns[0] . '">';
				$values=explode('(',$columns[1]);
				$values=explode(')',$values[1]);
				$values=explode(',',$values[0]);
				foreach ($values as $value) {
					$value=str_replace('\'','',$value);
					if ($value==$row_values[$columns[0]]) {
						echo '<option value="' . $value . '" selected="">' . $value . '</option>';
					}
					else {
						echo '<option value="' . $value . '">' . $value . '</option>';
					}
				}
				echo '</select><br/>';
			}
			else {
				echo '<textarea name="' . $columns[0] . '">' . $row_values[$columns[0]] . '</textarea><br />';
			}
		}
		elseif ($columns[0]=='organization_id') {
			echo '<label for="' . $columns[0] . '">' . $columns[0] . '</label><br />';
			$result=mysql_query('SELECT id, short_name FROM organizations ORDER BY id DESC');
			echo '<select name="' . $columns[0] . '">';
			while ($row=mysql_fetch_assoc($result)) {
				if ($row['id']==$row_values[$columns[0]]) {
					echo '<option value="' . $row['id'] . '" selected="">' . $row['short_name'] . "</option>";
				}
				else {
					echo '<option value="' . $row['id'] . '">' . $row['short_name'] . "</option>";
				}
			}
			echo '</select>';
			echo '<a href="add.php?table=organizations">Add new</a><br/>';
		}
	}
	echo '	<br />
			<input type="hidden" name="id" value="' . $_GET['id'] . '" />
			<input type="hidden" name="table" value="' . $table . '" />
			<input type="hidden" name="action" value="' . $action . '" />
			<input type="submit" value="' . $action . '" /><br /></form>';
	echo '<form action="show.php?" method="get"><input type="submit" value="back" /><br /></form>';
}


?>

</body>
</html>
