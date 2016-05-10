<?
if ( isset($_POST['member_id']) && isset($_GET['DOMAIN']) ) {
	require("app.php");
} else {
	// function pr_var($var, $title='') {
	// 	echo '<h3>'.$title.'</h3><br>count = _' . count($var). '_<pre>';
	// 	print_r($var);
	// 	echo '</pre>';
	// }
	// pr_var($_SERVER);
	// header('Location: http'
	// 	. ( ($_SERVER['SERVER_PORT'] != 80) ? 's' : '')
	// 	. '://' . $_SERVER['HTTP_HOST'] . '/apps/gtd');
	header('Location: https://' . $_SERVER['HTTP_HOST'] . '/apps/gtd');
	exit;
	// require("needToInstall.php");
}