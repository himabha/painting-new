<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/wp-config.php');

require_once('helper.php');

$helper = new Helper;
$helper->getAllTranslations("en");

$jsondata = file_get_contents(dirname(__FILE__).'/types.json');

$jsondata = json_decode($jsondata, true);


global $wpdb;

