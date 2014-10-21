<?php
/*
 * ЭТО ОСНОВНОЙ РАБОЧИЙ ФАЙЛ СИСТЕМЫ ИНТЕГРАЦИИ VOXILE
 * ВНЕШНИЕ СВЕТЛЫЕ СИЛЫ ОБРАЩАЮТСЯ К НЕМУ, ЧТОБЫ ВЫ УДОСТОВЕРИЛИ
 * ПОДЛИННОСТЬ НЕКОЙ ПЕРЕДАННОЙ УЧЁТНОЙ ЗАПИСИ
 */
define('VOXILE_SETTINGS_INCLUSION_CHECK', true);
define('VOXILE_FUNCTIONS_INCLUSION_CHECK', true);
require_once "voxileSettings.php";
require_once "voxileFunctions.php";

// Фильтрация и декодирование полученных данных
$encoded = filter_input(INPUT_POST, 'encoded', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
$decoded = explode("\0", voxile_decrypt($encoded));

if(count($decoded) == 3)
{
	// Разбиение принятых данных на составляющие
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
