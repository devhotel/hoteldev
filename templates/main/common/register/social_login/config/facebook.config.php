<?php 
require_once dirname(dirname(__FILE__)).'/facebook/facebook.php';
$facebook = new Facebook(array(
  'appId'  => '1376685939160319',
  'secret' => '53408a65f638aa0c2929f154167c67ee',
  'cookie' => true,
));
?>