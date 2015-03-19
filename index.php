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
	// XenForo 1.2.1 или совместимый
	$xenforo = auth_throught_xenforo($username, $password);
	exit(is_array($xenforo)
		? json_encode($xenforo)
		: $xenforo);
}

// Отрицательный результат
exit("NO");
