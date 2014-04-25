<?php
define('VOXILE_SETTINGS_INCLUSION_CHECK', true);
define('VOXILE_FUNCTIONS_INCLUSION_CHECK', true);
require_once "voxileSettings.php";
require_once "voxileFunctions.php";

// Фильтрация и декодирование полученных данных
$encoded = filter_input(INPUT_POST, 'encoded', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
$decoded = explode(":", voxile_decrypt($encoded));

if(count($decoded) == 3)
{
	$username = $decoded[0];
	$password = $decoded[1];
	$trashbox = $decoded[2];
	
	// Проверка валидности пары логина и пароля
	
	// XenForo 1.2.1 или совместимый
	$xenforo = voxile_auth_xenforo_v12x($username, $password);
	if($xenforo != false)
	{
		exit("AUTHENTICATED:$xenforo");
	}
}

// Отрицательный результат
exit("NO");
