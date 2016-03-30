<?
$url = 'https://at2.bitrix24.ru/oauth/authorize/?'
	. http_build_query(array(
		'client_id'=>'local.56ee9ad09ba9d4.64013109',
		"response_type" => "code",
		'redirect_uri'=>'http://tasks.gbdev.ru/gtd/'
	));

if(!empty($_GET["portal"])) {
echo $url;
	Header("HTTP 302 Found");
	Header("Location: ".$url);
	die();
}

?>
<!doctype html>
<html lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.css">
<title>GTD external</title>
</head>
<body class="container">
<pre>
<?
print_r($_REQUEST);
?>
</pre>
<?
require __DIR__.'/../../bootstrap/autoload.php';
$app = require_once __DIR__.'/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);


/*
define('CLIENT_ID', 'local.55fa7d514738b5.63819847');
define('CLIENT_SECRET', '9768f9ccc5b198837cab9b7f2f233e1e');
define('PATH', '/integration/index.php');
define('REDIRECT_URI', 'http://<SomeSite>' . PATH);
*/

// $obB24App = new \Bitrix24\Bitrix24();
// $arScope = array('user');

// $obB24App->setApplicationScope($arScope);
// $obB24App->setApplicationId(APP_ID); //из настроек в MP
// $obB24App->setApplicationSecret(APP_SECRET_CODE); //из настроек в MP
// $obB24App->setRedirectUri(APP_REG_URL);
// $obB24App->setDomain($domain);

// require_once('bitrix24.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('bitrix24');
$log->pushHandler(new StreamHandler($_SERVER['DOCUMENT_ROOT'] . '/log.txt', Logger::DEBUG));


// init lib
// $obB24App = new App\Bitrix24(false, $log);
//
//
/*
$obB24App = new Bitrix24\Bitrix24(false, $log);
$obB24App->setApplicationScope(array('user'));
$obB24App->setApplicationId('local.56ee9ad09ba9d4.64013109');
$obB24App->setApplicationSecret('e076a74cd254bb9982c0666cade373dc');

// set user-specific settings
$obB24App->setDomain('at2.bitrix24.ru');
$obB24App->setMemberId('9900950b81f028996748f3e1c3efb3aa');
*/

// $x = executeRequest('https://at2.bitrix24.ru/oauth/authorize/?client_id=local.56ee9ad09ba9d4.64013109&response_type=code');
// $url = 'https://at2.bitrix24.ru/oauth/authorize/?client_id=local.56ee9ad09ba9d4.64013109&response_type=code$redirect_uri=http://tasks.gbdev.ru/gtd/';

$curlOptions = array(
	CURLOPT_RETURNTRANSFER => true,
	// CURLINFO_HEADER_OUT => true,
	// CURLOPT_VERBOSE => true,
	// CURLOPT_CONNECTTIMEOUT => 5,
	// CURLOPT_TIMEOUT        => 5,
	// CURLOPT_USERAGENT => 'bitrix24\\bitrix24-php-sdk/v1.0"',
	// CURLOPT_POST => true,
	// CURLOPT_POSTFIELDS => http_build_query($additionalParameters),
	// CURLOPT_URL => $url
);

$curl = curl_init($url);
curl_setopt_array($curl, $curlOptions);

$curlResult = curl_exec($curl);
$log->debug('attempt to get code CURL', array(
	"curlOptions" => $curlOptions,
	"RESULT" => $curlResult
));
curl_close($curl);

$res = file_get_contents($url);
$log->debug('attempt to get code FGC', array(
	"RESULT" => $res
));
?>
<pre>
<?
print_r($curlResult);
?>
</pre>
<?
/*
$obB24App->setRedirectUri('https://tasks.gbdev.ru/gtd/');

$obB24App->getFirstAccessToken('nz4pnqir1utool8m51ceakfjajux5nu3');
// $obB24App->getNewAccessToken();
// $obB24App->setAccessToken($arParams['AUTH_ID']);
// $obB24App->setRefreshToken($arParams['REFRESH_ID']);

// get information about current user from bitrix24
$obB24User = new \Bitrix24\User\User($obB24App);
$arCurrentB24User = $obB24User->current();
*/
?>
	<h1>Задачи GTD</h1>
	<div id="app">
		<div class="description">
			Завершай свои задачи. Завершай вовремя.
		</div>
		<div class="tasks-types container">
			<div id="nav-tasks">
			</div>
		</div>
		<div class="tasks container-list container" id="tasks-container">
		</div>

	</div>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>