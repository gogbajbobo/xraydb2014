<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>Xray2014DB Index</title>
	<style type="text/css">
		body {background-color: lightgray;}
		table {border-style:solid; border-width:thin; margin: 1em 0; padding: .5em; border-collapse: collapse;}
		span {padding: 0 .25em 0 1em;}
		.authors {padding: 0;}
		.participants {background-color: #bef;}
		.organizations {background-color: #bef;}
		.papers {background-color: #fee;}
		.oral {background-color: #cfc;}
		.hotel {background-color: #fea;}
		.stand {background-color: #fee}
		th, td {padding: .5em 1em; text-align: left; border-top: solid black thin; vertical-align:text-top; font-size: 0.85em;}
		.summary td {text-align: right; background-color: #bef;}
		.authors {font-style: italic;}
		.small {font-size: 0.75em;}
		.paper {width: 25em;}
		.phone {width: 10em;}
		.note {width: 15em;}
		.bold {font-weight: bold}
	</style>
</head>
<body>
<?php

echo '<a href="show.php">Редактировать списки</a>';

$host='localhost';
$database='xray2014';
$dbuser='xraydb';
mysql_connect($host,$dbuser) or die('Connect error: ' . mysql_error());
mysql_query('SET NAMES utf8');
mysql_select_db($database) or die('Database select error: ' . mysql_error());

$emails=array();

$participant_count=mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM participants'));
$hotel_count=mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM participants WHERE hotel="yes"'));
$paper_count=mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM papers'));
$oral_count=mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM papers WHERE type="oral"'));
$stand_count=mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM papers WHERE type="stand"'));
$notext_count=mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM papers WHERE text="no"'));
$text_count=mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM papers WHERE text="yes"'));
$organizations_count=mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM organizations'));

echo '	<table class="summary">
			<tr>
				<td>
					<span><a href="?show=participants">Участников</a>: ' . $participant_count[0] . '</span><br/><br/>
					<span class="hotel"><a href="?show=participants&hotel=yes">Гостиница</a>: ' . $hotel_count[0] . '</span><br/>
				</td>
				<td>
					<span><a href="?show=papers">Докладов</a>: ' . $paper_count[0] . '</span><br/><br/>
					<span class="oral"><a href="?show=papers&type=oral">Устных</a>: ' . $oral_count[0] . '</span><br/>
					<span class="stand"><a href="?show=papers&type=stand">Стендовых</a>: ' . $stand_count[0] . '</span><br/>
					<span><a href="?show=papers&text=no">Нет текста доклада</a>: ' . $notext_count[0] . '</span><br/>
					<span><a href="?show=papers&text=yes">С текстом доклада</a>: ' . $text_count[0] . '</span><br/>
				</td>
				<td>
					<span><a href="?show=organizations">Организаций</a>: ' . $organizations_count[0] . '</span><br/><br/>
				</td>
			</tr>
		</table>' . "\r";

echo '	<table class="info';

if ($_GET['show']=='participants') {
	echo ' participants">';

	echo '<a href="?show=par">Список участников для сайта</a><br />';


	$query='SELECT * FROM participants';
	if ($_GET['hotel']=='yes') {
		$query.=' WHERE hotel="yes" ORDER BY organization_id, last_name';
	} else {
		$query.=' ORDER BY last_name';
	}
	echo $query;
	$result=mysql_query($query);
//	while ($row = mysql_fetch_assoc($result)) {
//		$test[$key] = $row['last_name'];
//		echo $row['last_name'];
//	}
	while ($row = mysql_fetch_assoc($result)) {
		$emails[$row['last_name'] . ' ' . $row['first_name'] . ' ' . $row['middle_name']]=$row['email'];
		echo '<tr';
		if ($row['hotel']=='yes') echo ' class="hotel"';
		echo '>';
		echo '<td>' . $i=$i+1 . '</td>';
		echo '<td><a href="mailto:' . $row['email'] . '?Subject=Конференция «Рентгеновская оптика — 2014»">' . $row['last_name'] . ' ' . $row['first_name'] . ' ' . $row['middle_name'] . '</a></td>';
		$organization=mysql_fetch_assoc(mysql_query('SELECT short_name, name FROM organizations WHERE id=' . $row['organization_id']));
		echo '<td>' . $organization['short_name'] . '</td>';
		echo '<td>' . $organization['name'] . '</td>';
		echo '<td>' .$row['degree']. '</td>';
		echo '<td>' .$row['position']. '</td>';
		echo '<td class="small phone">' .$row['phone']. '</td>';
		$paper_ids_result=mysql_query('SELECT paper_id FROM pp WHERE participant_id=' . $row['id']);
		//echo '<td class="small paper">';
		//while ($paper_ids=mysql_fetch_assoc($paper_ids_result)) {
		//	foreach ($paper_ids as $paper_id) {
		//		$paper_result=mysql_query('SELECT * FROM papers WHERE id=' . $paper_id);
		//		while ($paper=mysql_fetch_assoc($paper_result)) {
		//			$paper_type=($paper['type']=='oral')?'устный':'стендовый';
		//			echo '<span class="authors">' .$paper['authors']. '</span><br/>' .$paper['title']. '<br/>(' .$paper_type. ')<br/><br/>';
		//		}
		//	}
		//}
		//echo '</td>';
		echo '<td class="small note">' .$row['note']. '</td>';
		echo '</tr>';
	}
}

if ($_GET['show']=='papers') {
	echo ' papers">';
	echo '<a href="?show=pap">Список докладов для сайта</a><br />
	';

	$query='SELECT * FROM papers';
	if ($_GET['type']=='oral') {
		$query.=' WHERE type="oral"';
	} elseif ($_GET['type']=='stand') {
		$query.=' WHERE type="stand"';
	} elseif ($_GET['text']=='no') {
		$query.=' WHERE text="no"';
	} elseif ($_GET['text']=='yes') {
		$query.=' WHERE text="yes"';
	}
	$query.=' ORDER BY title';
	$result=mysql_query($query);
	while ($row=mysql_fetch_assoc($result)) {
		echo '<tr';
		if ($row['type']=='oral') echo ' class="oral"';
		echo '>';
		echo '<td>' . $i=$i+1 . '</td>';
		echo '<td class="paper"><span class="authors">' . $row['authors'] . '</span><br/>' . $row['title'] . '</td>';
		$participant_ids_result=mysql_query('SELECT participant_id FROM pp WHERE paper_id=' . $row['id']);
		echo '<td>';
		while ($participant_ids=mysql_fetch_assoc($participant_ids_result)) {
			foreach ($participant_ids as $participant_id) {
				$participant_result=mysql_query('SELECT * FROM participants WHERE id=' . $participant_id);
				while ($participant=mysql_fetch_assoc($participant_result)) {
					$organization=mysql_fetch_assoc(mysql_query('SELECT short_name FROM organizations WHERE id=' . $participant['organization_id']));
					$emails[$participant['last_name'] . ' ' . $participant['first_name'] . ' ' . $participant['middle_name']]=$participant['email'];
					echo	'<a href="mailto:' . $participant['email'] . '?Subject=Конференция «Рентгеновская оптика — 2014»">'
							. $participant['last_name'] . ' ' . $participant['first_name'] . ' ' . $participant['middle_name'] .
							'</a>' . '<br/>(' . $organization['short_name'] . ')<br/><br/>';
				}
			}
		}
		echo '</td>';
		echo '</tr>';
	}
}

if ($_GET['show']=='organizations') {
	echo ' organizations">';
	$query='SELECT * FROM organizations';
	$query.=' ORDER BY name';
	$result=mysql_query($query);
	while ($row=mysql_fetch_assoc($result)) {
		echo '<tr>';
		echo '<td>' . $i=$i+1 . '</td>';
		$count=mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM participants WHERE organization_id=' . $row['id']));
		echo '<td>' . $row['name'] . '</td>';
		echo '<td>' . $row['short_name'] . '</td>';
		echo '<td>' . $row['address'] . '</td>';
		echo '<td>' . $count[0] . '</td>';
		echo '</tr>';
	}
}

// Info for site

if ($_GET['show']=='par') {
	echo ' participants"></table>' . "\r";

	echo '<a href="?show=participants">Список участников</a><br />
	';

	echo '<div class="content">
	<p class="text bold large">Участники конференции</p>
	';

	$query='SELECT * FROM participants';
	$query.=' ORDER BY last_name';
	$result=mysql_query($query);

	$count = mysql_num_rows($result);
	$testCount = $count % 100;
	$count_str = '';
	$reg_str = 'Зарегистрировано ';
	if ($testCount >= 11 && $testCount <= 19) {
		$count_str = $count . ' участников';
	} else {
		$switchNumber = $testCount % 10;
		switch ($switchNumber) {
			case 1:
				$count_str = $count . ' участник';
				$reg_str = 'Зарегистрирован ';
				break;
            case 2:
            case 3:
            case 4:
                $count_str = $count . ' участника';
                break;
			default:
				$count_str = $count . ' участников';
				break;
		}
	}

	echo '<p class="text bold">' . $reg_str . '<span class="red">' . $count_str . '</span> Конференции.</p>
	<div class="list">
	';


	while ($row=mysql_fetch_assoc($result)) {

		echo '<div>
		';

		$emails[$row['last_name'] . ' ' . $row['first_name'] . ' ' . $row['middle_name']]=$row['email'];
		echo '<span class="full_name bold">' . $row['last_name'] . ' ' . $row['first_name'] . ' ' . $row['middle_name'] . '</span>
		<br />
		';
		$organization=mysql_fetch_assoc(mysql_query('SELECT * FROM organizations WHERE id=' . $row['organization_id']));
		echo '<span class="organization_name">' . $organization['name'] . '</span>
		<br />
		';
		echo '<span class="organization_short_name">' . $organization['short_name'] . '</span>
		<br />
		';
		if (stristr($organization['address'],'Москва')) {
			$organization_address = "Россия, Москва";
		} elseif (stristr($organization['address'],'Нижний') or stristr($organization['address'],'ИФМ')) {
			$organization_address = "Россия, Нижний Новгород";
		} elseif (stristr($organization['address'],'Черноголовка')) {
			$organization_address = "Россия, Черноголовка";
		} elseif (stristr($organization['address'],'Новосибирск')) {
			$organization_address = "Россия, Новосибирск";
		} elseif (stristr($organization['address'],'Калуга')) {
			$organization_address = "Россия, Калуга";
		} elseif (stristr($organization['address'],'Долгопрудный')) {
			$organization_address = "Россия, Долгопрудный";
		} elseif (stristr($organization['address'],'Берлин')) {
			$organization_address = "Германия, Берлин";
		} elseif (stristr($organization['address'],'Гренобль')) {
			$organization_address = "Франция, Гренобль";
		} elseif (stristr($organization['address'],'Ереван')) {
			$organization_address = "Армения, Ереван";
		} elseif (stristr($organization['address'],'Victoria')) {
			$organization_address = "Australia, Victoria";
		} elseif (stristr($organization['address'],'Великий')) {
			$organization_address = "Россия, Великий Новгород";
		} elseif (stristr($organization['address'],'Нальчик')) {
			$organization_address = "Россия, Нальчик";
		} elseif (stristr($organization['address'],'Сыктывкар')) {
			$organization_address = "Россия, Сыктывкар";
		} elseif (stristr($organization['address'],'Дубна')) {
			$organization_address = "Россия, Дубна";
		} elseif (stristr($organization['address'],'Воронеж')) {
			$organization_address = "Россия, Воронеж";
		} elseif (stristr($organization['address'],'Калининград')) {
			$organization_address = "Россия, Калининград";
		} elseif (stristr($organization['address'],'Санкт-Петербург') || stristr($organization['address'],'С.-Петербург')) {
			$organization_address = "Россия, Санкт-Петербург";
		} elseif (stristr($organization['address'],'Kouto')) {
			$organization_address = "Japan, Hyogo";
		} elseif (stristr($organization['address'],'Томск')) {
			$organization_address = "Россия, Томск";
		} elseif (stristr($organization['address'],'Сумы')) {
			$organization_address = "Украина, Сумы";
		} elseif (stristr($organization['address'],'Orsay')) {
			$organization_address = "France, Orsay";
		} elseif (stristr($organization['address'],'Dresden')) {
			$organization_address = "Германия, Дрезден";
		}
		else $organization_address = "";
		echo '<span class="organization_address">' .$organization_address. '</span>
		</div>
		';
	}

	echo '</div>
	</div>
	';

}

if ($_GET['show']=='par1') {
	echo ' participants">' . "\r";
	$query='SELECT * FROM participants';
	$query.=' ORDER BY last_name';
	$result=mysql_query($query);
	while ($row=mysql_fetch_assoc($result)) {
		$emails[$row['last_name'] . ' ' . $row['first_name'] . ' ' . $row['middle_name']]=$row['email'];
		echo '<div>';
		echo '<span class="full_name bold">' . $row['last_name'] . ' ' . $row['first_name'] . ' ' . $row['middle_name'] . '</span><br/>';
		$organization=mysql_fetch_assoc(mysql_query('SELECT * FROM organizations WHERE id=' . $row['organization_id']));
		echo '<span class="organization_name">' . $organization['name'] . '</span><br/>';
		echo '<span class="organization_short_name">' . $organization['short_name'] . '</span><br/>';

		if (stristr($organization['address'],'Москва')) {
			$organization_address = "Россия, Москва";
		} elseif (stristr($organization['address'],'Нижний') || stristr($organization['address'],'ИФМ')) {
			$organization_address = "Россия, Нижний Новгород";
		} elseif (stristr($organization['address'],'Черноголовка')) {
			$organization_address = "Россия, Черноголовка";
		} elseif (stristr($organization['address'],'Cыктывкар')) {
			$organization_address = "Россия, Cыктывкар";
		} elseif (stristr($organization['address'],'Новосибирск')) {
			$organization_address = "Россия, Новосибирск";
		} elseif (stristr($organization['address'],'Берлин')) {
			$organization_address = "Германия, Берлин";
		} elseif (stristr($organization['address'],'Ереван')) {
			$organization_address = "Армения, Ереван";
		} elseif (stristr($organization['address'],'Victoria')) {
			$organization_address = "Australia, Victoria";
		} elseif (stristr($organization['address'],'Великий')) {
			$organization_address = "Россия, Великий Новгород";
		} elseif (stristr($organization['address'],'Санкт-Петербург') || stristr($organization['address'],'С.-Петербург')) {
			$organization_address = "Россия, Санкт-Петербург";
		} elseif (stristr($organization['address'],'Kouto')) {
			$organization_address = "Japan, Hyogo";
		} elseif (stristr($organization['address'],'Томск')) {
			$organization_address = "Россия, Томск";
		} elseif (stristr($organization['address'],'Сумы')) {
			$organization_address = "Украина, Сумы";
		} elseif (stristr($organization['address'],'Orsay')) {
			$organization_address = "France, Orsay";
		} elseif (stristr($organization['address'],'Dresden')) {
			$organization_address = "Германия, Дрезден";
		}
		else $organization_address = "";
		echo '<span class="organization_address">' .$organization_address. '</span><br/>';
		echo '<span class="phone">' .$row['phone']. '</span><br/>';
		echo '<span class="email"><a href="mailto:' .$row['email']. '">' .$row['email']. '</a></span><br/><br/>';
		echo '</div>' . "\r";
	}
}

if ($_GET['show']=='pap') {
	echo ' papers"></table>' . "\r";

	echo '<a href="?show=papers">Список докладов</a><br />
	';

	echo '<div class="content">
	<p class="text bold large">Список докладов</p>
	';

	$query='SELECT * FROM papers';
	$query.=' ORDER BY title';
	$result=mysql_query($query);

	$count = mysql_num_rows($result);
	// echo $count;
	$testCount = $count % 100;
	$count_str = '';
	$reg_str = ' подано ';
	if ($testCount >= 11 && $testCount <= 19) {
		$count_str = $count . ' докладов';
	} else {
		$switchNumber = $testCount % 10;
		switch ($switchNumber) {
			case 1:
				$count_str = $count . ' доклад';
				$reg_str = ' подан ';
				break;
            case 2:
            case 3:
            case 4:
                $count_str = $count . ' доклада';
                break;
			default:
				$count_str = $count . ' докладов';
				break;
		}
	}

	echo '<p class="text bold">На Конференцию' . $reg_str . '<span class="red">' . $count_str . '</span>.</p>
	<div class="list">
	<table class="info papers">
	<tbody>
	';


	while ($row=mysql_fetch_assoc($result)) {
		echo '<tr>
		';
		// echo '<td class="number">' . $i=$i+1 . '</td>';
		echo '<td class="paper">
		<span class="authors">' . $row['authors'] . '</span>
		<br/>
		' . $row['title'] . '
		</td>
		';
		$participant_ids_result=mysql_query('SELECT participant_id FROM pp WHERE paper_id=' . $row['id']);
		echo '<td class="participant">
		';
		while ($participant_ids=mysql_fetch_assoc($participant_ids_result)) {
			foreach ($participant_ids as $participant_id) {
				$participant_result=mysql_query('SELECT * FROM participants WHERE id=' . $participant_id);
				while ($participant=mysql_fetch_assoc($participant_result)) {
					$organization=mysql_fetch_assoc(mysql_query('SELECT short_name FROM organizations WHERE id=' . $participant['organization_id']));
					$emails[$participant['last_name'] . ' ' . $participant['first_name'] . ' ' . $participant['middle_name']]=$participant['email'];
					echo $participant['last_name'] . ' ' . $participant['first_name'] . ' ' . $participant['middle_name'] .
							'
							<br/>
							<span class="org">(' . $organization['short_name'] . ')</span>
							<br/>
							<br/>
							';
				}
			}
		}
		echo '</td>
		';
		echo '</tr>
		';
	}

	echo '</tbody>
	</table>
	</div>
	</div>
	</div>
	';

}


// End of Info for site

echo '	</table><br/>' . "\r";
$emails=array_unique($emails);
echo '<form action="composemail.php?" method="post">';
foreach ($emails as $key=>$value) {
	echo '<input type="hidden" name="' . $key . '" value="' . $value . '" />';
}
echo '<input type="submit" value="Compose e-mail" />';
echo '</form>';

?>
</body>
</html>
