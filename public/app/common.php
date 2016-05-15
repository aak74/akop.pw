<?
function pr_var($var, $title='') {
	echo '<h3>'.$title.'</h3><br>count = _' . count($var). '_<pre>';
	print_r($var);
	echo '</pre>';
}

require $_SERVER["DOCUMENT_ROOT"] . '/../bootstrap/autoload.php';

$app = require_once $_SERVER["DOCUMENT_ROOT"] . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

function registerApp ($code) {
	return \App\AppPortal::register([
		'member_id' => htmlspecialchars( $_POST['member_id'] ),
		'domain' => htmlspecialchars( $_GET['DOMAIN'] ),
		'app_code' => $code
	]);
}