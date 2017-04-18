<?php
define('CONTROLLERS',dirname(__FILE__) .'/engine/controllers/');
define('DB',dirname(__FILE__) .'/engine/database/');
define('SETTINGS',dirname(__FILE__) .'/engine/');
require_once DB.'rb.php';
require_once CONTROLLERS.'request.php';
require_once CONTROLLERS.'message.php';
require_once CONTROLLERS.'config.php';
require_once CONTROLLERS.'markers.php';
$config = new Config();
R::setup( 'mysql:host='.$config->db_host.';dbname='.$config->db_name.'', $config->db_user, $config->db_password );

$marker =  R::getAll( 'SELECT * FROM markers WHERE marker_state <> :marker_state LIMIT 1',[':marker_state' => "marker_done" ]); 


print_r($marker[0]['marker_state']);