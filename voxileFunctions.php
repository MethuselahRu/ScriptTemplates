<?php
if(!defined('VOXILE_FUNCTIONS_INCLUSION_CHECK'))
{
	die();
}

$voxileConfig['projectCode'] = strtoupper($voxileConfig['projectCode']);
$voxileConfig['aes256key'] = hash(
	"SHA256",
	$voxileConfig['projectCode'] . $voxileConfig['secretKeyword'],
	true);
$voxileConfig['iv'] = str_repeat("=", mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC));

function voxile_encrypt($plain2crypted)
{
	global $voxileConfig;
	return bin2hex(rtrim(
		base64_encode(
			mcrypt_encrypt(
				MCRYPT_RIJNDAEL_256,
				$voxileConfig['aes256key'], $plain2crypted,
				MCRYPT_MODE_CBC, $voxileConfig['iv'])),
		"\0\3"));
}
function voxile_decrypt($crypted2plain)
{
	global $voxileConfig;
	return rtrim(
		mcrypt_decrypt(
			MCRYPT_RIJNDAEL_256,
			$voxileConfig['aes256key'], base64_decode(hex2bin($crypted2plain)),
			MCRYPT_MODE_CBC, $voxileConfig['iv']
		), "\0\3");
}

function voxile_auth_xenforo_v12x($login, $password)
{
	global $voxileConfig;
	$xenForoPath = $voxileConfig['xenForoPath'];
	require($xenForoPath . "/library/XenForo/Autoloader.php");
	XenForo_Autoloader::getInstance()->setupAutoloader($xenForoPath . "/library/");
	XenForo_Application::initialize($xenForoPath . "/library", $xenForoPath);
	XenForo_Application::set('page_start_time', microtime(true));
	$application = XenForo_Application::get('db');
	if(isset($login) && isset($password))
	{
		$query = "SELECT `user_id` FROM `xf_user` WHERE `username` = '" . $application->quote($login) . "'";
		$result = $application->fetchCol($query);
		if(count($result))
		{
			$user_id = $result[0];
			$query = "SELECT `username` FROM `xf_user` WHERE `username` = '" . $application->quote($login) . "'";
			$result = $application->fetchCol($query);
			if(count($result))
			{
				$username = $result[0];
				$auth = new XenForo_Authentication_Core12;
				$query = "SELECT `data` FROM `xf_user_authenticate` WHERE `user_id` = '$user_id'";
				$result = $application->fetchCol($query);
				if(count($result))
				{
					$auth->setData($result[0]);
					$is_valid = $auth->authenticate($user_id, $password);
					return $is_valid ? $username : false;
				}
			}
		}
	}
	return false;
}
