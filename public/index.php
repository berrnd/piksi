<?php

$datapath = __DIR__ . '/../data';
if (getenv('PIKSI_DATAPATH') !== false)
{
	$datapath = getenv('PIKSI_DATAPATH');
}
elseif (array_key_exists('PIKSI_DATAPATH', $_SERVER))
{
	$datapath = $_SERVER['PIKSI_DATAPATH'];
}
define('PIKSI_DATAPATH', $datapath);

require_once __DIR__ . '/../app.php';
