<?php
/*
 * ЭТО ОСНОВНОЙ РАБОЧИЙ ФАЙЛ СИСТЕМЫ ИНТЕГРАЦИИ VOXILE
 * ВНЕШНИЕ СВЕТЛЫЕ СИЛЫ ОБРАЩАЮТСЯ К НЕМУ, ЧТОБЫ ВЫ УДОСТОВЕРИЛИ
 * ПОДЛИННОСТЬ НЕКОЙ ПЕРЕДАННОЙ УЧЁТНОЙ ЗАПИСИ
 */
define('METHUSELAH_INCLUSION_CHECK', true);
require_once "settings.php";
require_once "encryption.php";
require_once "authFunctions.php";

// Фильтрация и декодирование полученных данных
$encryption = setupEncryption($config['projectCode'], $config['secretKeyword']);
$payload    = file_get_contents("php://input");
$decrypted  = third_party_decrypt($payload, $encryption);
$decrypted  = json_decode($decrypted, true);
$username   = $decrypted['username'];
$password   = $decrypted['password'];

if(isset($username) && isset($password))
{
	switch($config['cmsType'])
	{
	case "XENFORO_PRE12":
		$xenforo = auth_throught_xenforo1x($username, $password, true);
		exit(is_array($xenforo)
			? json_encode($xenforo)
			: $xenforo);
	case "XENFORO_LATEST_1x":
		$xenforo = auth_throught_xenforo1x($username, $password, false);
		exit(is_array($xenforo)
			? json_encode($xenforo)
			: $xenforo);
	default:
		die("CONFIGURATION BROKEN -- UNKNOWN ENGINE HAS BEEN SET");
	}
}

// Отрицательный результат
exit("NO");
