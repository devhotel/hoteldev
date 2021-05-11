<?php 
require_once("../../../common/lib.php");
require_once("../../../common/define.php");
$response = array("html" => "", "notices" => array(), "error" => "", "success" => "", "redirect" => "");
if(isset($db) && $db !== false && isset($_POST)){
        $sql = "DELETE FROM pm_wishlist WHERE id=".$_POST['data_id'];
        $db->exec($sql);
        $response['success'] = 'Wishlist remove successfully';
    
    
    echo json_encode($response);
}
?>