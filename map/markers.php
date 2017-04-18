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

$marker =  R::getAll( 'SELECT * FROM markers WHERE marker_state = :marker_state',[':marker_state' => "marker_done" ]); 



function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}



header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
for($i=0;$i<count($marker);$i++)
{
  // Add to XML document node
  echo '<marker ';
  echo 'id="' . $marker[$i]['id'] . '" ';
  echo 'name="' . parseToXML($marker[$i]['marker_name']) . '" ';
  echo 'address="' . parseToXML($marker[$i]['marker_address']) . '" ';
  echo 'lat="' . $marker[$i]['marker_lat'] . '" ';
  echo 'lng="' . $marker[$i]['marker_lng'] . '" ';
  echo 'type="' . $marker[$i]['marker_type'] . '" ';
  echo '/>';
}

// End XML file
echo '</markers>';




