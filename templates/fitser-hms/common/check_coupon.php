<?php
/**
 * Script called (Ajax) on customer update
 * fills the customer fields in the booking form
 */
//session_start();
require_once('../../../common/lib.php');
require_once('../../../common/define.php');

$response = array('html' => '', 'stat' => '');
if(isset($_SESSION['book']['total']) && @$_SESSION['book']['total']>0){
 if(isset($_POST['coupon_code']) && $_POST['coupon_code'] != ''){
                $coupon_code = htmlentities($_POST['coupon_code'], ENT_COMPAT, 'UTF-8');
                $result_coupon = $db->query('SELECT * FROM pm_coupon WHERE checked = 1 AND UPPER(code) = UPPER('.$db->quote($coupon_code).') AND discount > 0 AND (publish_date IS NULL || publish_date <= '.time().') AND (unpublish_date IS NULL || unpublish_date > '.time().') LIMIT 1');
                if($result_coupon !== false && $db->last_row_count() > 0){
                    $row = $result_coupon->fetch();
                    $response['html'] = '<span class="success">'.$texts['COUPON_CODE_SUCCESS'].'</span>';
                    $response['stat']=1;
                }else{
                    $response['html'] ='<span class="error"> No coupon available</span>';
                    $response['stat']=0;
                }
            }else{
                $response['html'] ='<span class="error"> Plaese enter coupon code!!</span>'; 
                $response['stat']=0;
            }
}else{
    $response['html'] ='<span class="error"> Plaese choose a room !!</span>'; 
    $response['stat']=0;
}
echo json_encode($response);           
exit();

