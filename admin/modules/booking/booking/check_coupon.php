<?php
/**
 * Script called (Ajax) on customer update
 * fills the customer fields in the booking form
 */
session_start();
if(!isset($_SESSION['admin'])) exit();

if(defined('DEMO') && DEMO == 1) exit();

define('ADMIN', true);
require_once('../../../../common/lib.php');
require_once('../../../../common/define.php');
$response = array('html' => '', 'notices' => array(), 'error' => '', 'success' => '', 'extraHtml' => '');
if(isset($_SESSION['bt']['total']) && @$_SESSION['bt']['total']>0){
 if(isset($_POST['coupon_code']) && $_POST['coupon_code'] != ''){
                $coupon_code = htmlentities($_POST['coupon_code'], ENT_COMPAT, 'UTF-8');
                $result_coupon = $db->query('SELECT * FROM pm_coupon WHERE checked = 1 AND UPPER(code) = UPPER('.$db->quote($coupon_code).') AND discount > 0 AND (publish_date IS NULL || publish_date <= '.time().') AND (unpublish_date IS NULL || unpublish_date > '.time().') LIMIT 1');
                if($result_coupon !== false && $db->last_row_count() > 0){
                    $row = $result_coupon->fetch();
                    echo '<span class="success">'.$texts['COUPON_CODE_SUCCESS'].'</span>';
                }else{
                    echo '<span class="error"> No coupon available</span>';
                }
            }else{
                echo '<span class="error"> Plaese enter coupon code!!</span>'; 
            }
}else{
     echo '<span class="error"> Plaese choose a room !!</span>'; 
}
            
exit();

