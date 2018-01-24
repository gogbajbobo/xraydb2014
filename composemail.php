<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Xray2014DB Compose e-mail</title>
	<style type="text/css">
		textarea {width: 50em;}
		#text {height: 15em;}
	</style>
	<script type="text/javascript">
		function checkAll(){
			var inputs = document.getElementsByTagName('input');
			for (var i=0; i<inputs.length; i++) {
				var input = inputs[i];
				if (input.getAttribute('type')=='checkbox') {
					input.setAttribute('checked','checked');
				}
			}
		}
		function uncheckAll() {
			var inputs = document.getElementsByTagName('input');
			for (var i=0; i<inputs.length; i++) {
				var input = inputs[i];
				if (input.getAttribute('type')=='checkbox') {
					input.removeAttribute('checked');
				}
			}
		}
	</script>
</head>
<body>

<form action="sendemail.php?" method="post">
<label for="subject">Subject:</label><br/>
<textarea id="subject" name="subject">Конференция «Рентгеновская оптика — 2014», г. Черноголовка</textarea><br/><br/>
<label for="text">Text:</label><br/>
<textarea id="text" name="text">Дорогие коллеги!

С 6 по 9 октября 2014 года в г. Черноголовка на базе Федерального государственного бюджетного учреждения науки Института проблем технологии микроэлектроники и особочистых материалов РАН (ИПТМ РАН) состоится конференция «Рентгеновская оптика — 2014».

С уважением, орг.комитет конференции «Рентгеновская оптика — 2014».</textarea><br/><br/>

<button type="button" onclick="checkAll()">Check all</button>
<button type="button" onclick="uncheckAll()">Uncheck all</button>
<br/>
<span>Send to:</span><br/>

<?php

foreach ($_POST as $name=>$email) {
	$name=implode(' ',explode('_',$name));
	$i=$i+1;
	echo '<input type="checkbox" name="address' .$i. '" value="' .$email. '" checked="checked"/> ';
	echo '<label for="address' .$i. '">' .$name. '</label><br/>';
}

?>
<label for="customails">Add e-mails:</label><br/>
<textarea id="customails" name="customails">xxx@yyy.zzz</textarea>
<br/>
<input type="submit" value="Send e-mail" />
</form>
<br/>
<a href="index.php">Back to View</a>
</body>
</html>
