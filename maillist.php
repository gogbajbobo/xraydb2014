<?php

date_default_timezone_set('Europe/Moscow');

function read_smtp_answer($socket) {			// Функция для чтения ответа сервера. Выбрасывает исключение в случае ошибки
	$read = socket_read($socket, 1024);
	if ($read{0} != '2' && $read{0} != '3') {
		if (!empty($read)) {
			throw new Exception('SMTP failed: '.$read."\n");
		} else {
			throw new Exception('Unknown error'."\n");
		}
	}
}

function write_smtp_response($socket, $msg) {	// Функция для отправки запроса серверу
	$msg = $msg."\r\n";
	socket_write($socket, $msg, strlen($msg));
}

function SendMail($email,$subject,$text) {
	$address = 'mail.iptm.ru'; // адрес smtp-сервера
	$port    = 25;          // порт (стандартный smtp - 25)
	// $from    = '=?utf-8?b?' . base64_encode('Конференция «Рентгеновская оптика»') . '?= <x-ray@iptm.ru>';  // адрес отправителя
	$from    = 'x-ray@iptm.ru';  // адрес отправителя

	try {

		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);	// Создаем сокет
		if ($socket < 0) {
			throw new Exception('socket_create() failed: '.socket_strerror(socket_last_error())."\n");
		}

		$result = socket_connect($socket, $address, $port);	// Соединяем сокет к серверу
		if ($result === false) {
			throw new Exception('socket_connect() failed: '.socket_strerror(socket_last_error())."\n");
		}

		read_smtp_answer($socket);	// Читаем информацию о сервере

		write_smtp_response($socket, 'EHLO '.$_SERVER['SERVER_NAME']);	// Приветствуем сервер
		read_smtp_answer($socket); // ответ сервера

		write_smtp_response($socket, 'MAIL FROM:<'.$from.'> BODY=8BITMIME');	// Задаем адрес отправителя
		read_smtp_answer($socket); // ответ сервера

		write_smtp_response($socket, 'RCPT TO:<'.$email.'>');	// Задаем адрес получателя
		read_smtp_answer($socket); // ответ сервера

		write_smtp_response($socket, 'DATA');	// Готовим сервер к приему данных
		read_smtp_answer($socket); // ответ сервера

		$header = "From: " . $from . "\r\n";
		$header .= "To: " . $email . "\r\n";
		$header .= "Subject: =?utf-8?b?" . base64_encode($subject) . "?=\r\n";
		$header .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
		$header .= "Content-Transfer-Encoding: base64\r\n";
		$header .= "Mime-Version: 1.0\r\n";
		$text = $header . base64_encode($text);

		write_smtp_response($socket, $text."\r\n.");	// Отправляем данные
		read_smtp_answer($socket); // ответ сервера

		write_smtp_response($socket, 'QUIT');	// Отсоединяемся от сервера
		read_smtp_answer($socket); // ответ сервера

	} catch (Exception $e) {
		echo "\nError: ".$e->getMessage();
	}

	if (isset($socket)) {
		socket_close($socket);
	}

}

$email_list = file_get_contents('xray_conf_emails.txt');

// var_dump(explode(',', $email_list));

// echo '<br />';

$emails = array_unique(explode(',', $email_list));

// $emails[] = 'xxx@yyy.zzz';

// var_dump($emails);

// echo implode(',', $emails);

echo '<br />';

$subject = 'Конференция «Рентгеновская оптика — 2014»';
$text = 'Уважаемые коллеги!

Напоминаем вам, что регистрация на конференцию «Рентгеновская оптика — 2014» заканчивается 15 мая 2014 г.
Если вы уже присылали регистрационную карточку, то просим вас проверить наличие и точность информации о вашем докладе на сайте конференции.

http://purple.iptm.ru/xray/xray2014/

С уважением, оргкомитет.
x-ray@iptm.ru
';

/* Тестовый блок */
/* ------------- */
foreach ($emails as $email) {
	echo date('h:i:s') . ' — ';
	echo $email . '<br/>';
}

echo 'Subject: ' . $subject . '<br/>';
echo 'Text: ' . $text . '<br/>';
/* ------------- */
/* Тестовый блок */

/* Отправка писем */
/* -------------- *
	foreach ($emails as $email) {
	echo date('h:i:s') . ' — ' .$email. '<br/>';
	SendMail($email,$subject,$text);
	sleep(1);
}
/* -------------- */
/* Отправка писем */

echo '<a href="index.php">Back to View</a>';


?>
