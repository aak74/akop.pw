<?
if ( isset($_POST['member_id']) && isset($_GET['DOMAIN']) ) {
	require $_SERVER["DOCUMENT_ROOT"] . '/app/common.php';
	$data = registerApp('backlog');
	$_GLOBALS['version_path'] = 'versions/' . $data->version . '/';
	require($_GLOBALS['version_path'] . 'app.php');
} else {
	header('Location: https://' . $_SERVER['HTTP_HOST'] . '/apps/gtd');
	exit;
}