<?
error_reporting(E_ALL);
ini_set("display_errors", 1);

// ini_set('display_errors', 1);

function pr_var($var, $title='') {
	echo '<h3>'.$title.'</h3><br>count = _' . count($var). '_<pre>';
	print_r($var);
	echo '</pre>';
}

pr_var($_REQUEST, '$_POST');
// pr_var($_SERVER, '$_SERVER');

require $_SERVER["DOCUMENT_ROOT"] . '/../bootstrap/autoload.php';

$app = require_once $_SERVER["DOCUMENT_ROOT"] . '/../bootstrap/app.php';
// pr_var($app, 'app');
// dd($app);
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// $response->send();

// $kernel->terminate($request, $response);

use App\Portal;

$data = Portal::where(['member_id' => "dasjdh jkdhjkashdjk l"])->get();
if ( empty($data) ) {
	Portal::create(['name' => "dasda", 'member_id' => "dasjdh jkdhjkashdjk l"]);
	Portal::save();


}

// $data = Portal::where(['member_id' => htmlspecialchars( $_POST['member_id'] )])->get();
// $data = Portal::firstOrCreate(['member_id' => htmlspecialchars( $_POST['member_id'] )])->get();
// if (empty($data)) {
// 	App\Portal::add();
// }
echo "111222";

// pr_var($data, "data");
?>