<!doctype html>
<html lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="<?=$_GLOBALS['version_path']?>css/style.css?<?=microtime()?>">
<title>Задачи GTD</title>
</head>
<body class="container">
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
<!-- <script src="_api/v1.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.min.js"></script>
<script src="<?=$_GLOBALS['version_path']?>js/api.js?<?=microtime()?>"></script>
<script src="https://api.bitrix24.com/api/v1/"></script>
<script src="<?=$_GLOBALS['version_path']?>js/app.js?<?=microtime()?>"></script>

</body>
</html>