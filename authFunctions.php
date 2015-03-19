<?php
/*
 * ЭТО СЛУЖЕБНЫЙ ФАЙЛ СИСТЕМЫ ИНТЕГРАЦИИ METHUSELAH
 * НЕ ИЗМЕНЯЙТЕ ЕГО БЕЗ ПОНИМАНИЯ ПРОИСХОДЯЩЕГО
 */
if(!defined('METHUSELAH_INCLUSION_CHECK'))
{
	die();
}

function auth_throught_xenforo($username, $password)
{
	global $config;
	$xenForoPath = $config['xenForoPath'];
	require($xenForoPath . "/library/XenForo/Autoloader.php");
	XenForo_Autoloader::getInstance()->setupAutoloader($xenForoPath . "/library/");
	XenForo_Application::initialize($xenForoPath . "/library", $xenForoPath);
	XenForo_Application::set('page_start_time', microtime(true));
	$application = XenForo_Application::get('db');
	if(isset($username) && isset($password))
	{
		$query = "SELECT `user_id` FROM `xf_user` WHERE `username` = '$username'";
		$result = $application->fetchCol($query);
		if(count($result))
		{
			$user_id = $result[0];
			$query = "SELECT `username` FROM `xf_user` WHERE `username` = '$username'";
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
					return $is_valid
						? array("name" => $username, "id" => $user_id,)
						: "USER FOUND BUT PASSWORD IS WRONG";
				}
				return "XF INTERNAL ERROR (1)";
			}
			return "XF INTERNAL ERROR (2)";
		}
		return "NO SUCH PLAYER";
	}
	return "USERNAME OR PASSWORD IS UNSET";
}
