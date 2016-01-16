<html lang="en">
<head>
	<title>Xray20124B SQL</title>
	<style type="text/css">
		input, textarea, select {width: 25em;}
		input, textarea, select {margin: 1.5em;}
	</style>
</head>
<body>

<?php

//foreach ($_GET as $key=>$value) echo $key . ' - ' . $value . '<br />';
$host='localhost';
$database='xray2014';
$dbuser='xraydb';
mysql_connect($host,$dbuser) or die(mysql_error());
mysql_query('SET NAMES utf8');
mysql_select_db($database) or die(mysql_error());

$table=$_GET['table'];
$action=$_GET['action'];
$id=$_GET['id'];

if (isset($action)) {

	if ($action=='add') {

		$result=mysql_query('SHOW fields FROM ' . $table);
		$query='insert into ' . $table . ' values (NULL';

		while ($columns=mysql_fetch_array($result)) {

			if ($columns[0]!='id') {

				if (substr_count($columns[1],'int')>0) {
					$query=$query . ',' . $_GET[$columns[0]];
				}
				else {
					$query=$query . ',"' . $_GET[$columns[0]] . '"';					
				}

			}

		}

		$query=$query . ')';

	} elseif ($action=='del') {

		$query='DELETE FROM ' . $table . ' WHERE id=' . $id;
		if ($table=='participants'or$table=='papers') $query1='DELETE FROM pp WHERE '. substr($table,0,-1) . '_id=' . $id;
	
	} elseif ($action=='modify') {

		$result=mysql_query('SHOW fields FROM ' . $table);
		$query='UPDATE ' . $table . ' SET ';

		while ($columns=mysql_fetch_array($result)) {

			if ($columns[0]!='id') {

				if (substr_count($columns[1],'int')>0) {
					$query=$query . $columns[0] . '=' . $_GET[$columns[0]] . ', ';
				}
				else {
					$query=$query . $columns[0] . '="' . $_GET[$columns[0]] . '", ';					
				}

			}

		}

		$query = implode(',', explode(',', $query, -1));
		$query=$query . ' WHERE id=' . $_GET['id'];

	}

	echo $query . '<br/>';
	echo $query1 . '<br/>';

}

mysql_query($query);
mysql_query($query1);

echo '<form action="show.php?" method="get">';
echo '<input type="hidden" name="ShowTable" value="' . $table . '" />';
echo '<input type="submit" value="Show ' . $table . '" /><br /></form>';

?>

</body>
</html>