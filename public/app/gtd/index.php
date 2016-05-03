<?
if ( isset($_POST['member_id']) && isset($_GET['DOMAIN']) ) {
	require("app.php");
} else {
	require("needToInstall.php");
}