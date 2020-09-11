<?php 
debug_backtrace() || die ('Direct access not permitted');
define('ADMIN', true);

define('SYSBASE', str_replace('\\', '/', realpath(dirname(__FILE__).'/../../../').'/'));

require_once(SYSBASE.'common/lib.php');
require_once(SYSBASE.'common/define.php');

var_dump($_POST);
if(!isset($_SESSION['ghs'])){
    header('Location: '.DOCBASE.'ghstats/login.php');
    exit();
}
?>
