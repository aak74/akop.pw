<?php
session_start();

/**
 * client_id приложения
 */
define('CLIENT_ID', 'local.572494a8deb564.76598859');
/**
 * client_secret приложения
 */
define('CLIENT_SECRET', '06455b6b9742eb49de8e904b5c0ae435');
/**
 * относительный путь приложения на сервере
 */
define('PATH', '/3/');
/**
 * полный адрес к приложения
 */
define('REDIRECT_URI', 'http://akop.pw'.PATH);
/**
 * scope приложения
 */
define('SCOPE', 'task,user');

/**
 * протокол, по которому работаем. должен быть https
 */
define('PROTOCOL', "https");

/**
 * Производит перенаправление пользователя на заданный адрес
 *
 * @param string $url адрес
 */
function redirect($url)
{
	Header("HTTP 302 Found");
	Header("Location: ".$url);
	die();
}

/**
 * Совершает запрос с заданными данными по заданному адресу. В ответ ожидается JSON
 *
 * @param string $method GET|POST
 * @param string $url адрес
 * @param array|null $data POST-данные
 *
 * @return array
 */
function query($method, $url, $data = null)
{
	$query_data = "";

	$curlOptions = array(
		CURLOPT_RETURNTRANSFER => true
	);

	if($method == "POST")
	{
		$curlOptions[CURLOPT_POST] = true;
		$curlOptions[CURLOPT_POSTFIELDS] = http_build_query($data);
	}
	elseif(!empty($data))
	{
		$url .= strpos($url, "?") > 0 ? "&" : "?";
		$url .= http_build_query($data);
	}

	$curl = curl_init($url);
	curl_setopt_array($curl, $curlOptions);
	$result = curl_exec($curl);

	return json_decode($result, 1);
}

/**
 * Вызов метода REST.
 *
 * @param string $domain портал
 * @param string $method вызываемый метод
 * @param array $params параметры вызова метода
 *
 * @return array
 */
function call($domain, $method, $params)
{
	return query("POST", PROTOCOL."://".$domain."/rest/".$method, $params);
}