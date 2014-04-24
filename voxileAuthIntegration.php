<?php
define('VOXILE_FUNCTIONS_INCLUSION_CHECK', true);
require_once "voxileFunctions.php";

$encoded = filter_input(INPUT_POST, 'encoded', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
$decoded = explode(":", voxile_decrypt($encoded));

if(count($decoded) == 3)
{
	$username = $decoded[0];
	$password = $decoded[1];
	$trashbox = $decoded[2];
	
	$xenforo = voxile_auth_xenforo($username, $password);
	if($xenforo != false)
	{
		exit("AUTHENTICATED:$xenforo");
	}
}
exit("NO");
