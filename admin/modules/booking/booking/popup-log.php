<?php
define("ADMIN", true);
define("SYSBASE", str_replace("\\", "/", realpath(dirname(__FILE__)."/../../../../")."/"));
require_once(SYSBASE."common/lib.php");
require_once(SYSBASE."common/define.php");

if(!isset($_SESSION['admin'])) exit();
elseif($_SESSION['admin']['type'] == "registered"){
    unset($_SESSION['admin']);
    exit();
}else{}

?>
<html>
<?php
if(isset($_POST['id']) && isset($_SESSION['admin']['id'])){
    $id_booking = (int)$_POST['id'];

    if(is_numeric($id_booking)){
        
    ?>

    <head>
        <?php include(SYSBASE.ADMIN_FOLDER.'/includes/inc_header_list.php'); ?>
        
       
        </head>
        <body>
        <div class="white-popup-block">
            <?php
              $result_booking = $db->query('SELECT * FROM pm_booking WHERE id = '.$id_booking);
               if($result_booking !== false && $db->last_row_count() > 0){
                $row = $result_booking->fetch();
            ?>
            <div id="popup-booking-<?php echo $id_booking; ?>">
            <?php
                echo '
                <h2>Booking Activity Logs #'.$id_booking.'</h2>';
                    $result_activity = $db->query('SELECT * FROM pm_activity_log WHERE id_item = '.$row['id']);
               if($result_activity !== false && $db->last_row_count() > 0){
                    echo '
                    <table class="table table-responsive table-bordered" id="ativity_log">';
                        foreach($result_activity as $activity){
                            //var_dump($activity);
                            echo
                            '<tr>
                                <td>'.$activity['module']. ' '.$activity['action'].'<br>'.$activity['purpose'].'<br>  By '.db_getFieldValue($db, 'pm_user', 'firstname', $activity['user_id'], $lang = 0).'</td>
                                <td>'.date("d/m/Y h:ia", $activity['add_date']).'</td>
                            </tr>';
                        }
                        echo '
                      </table>';
                   }else{
                    echo '<p> Acitivity Log not found!! </p>';
                  } 	
                
             ?>  
        </div>
        <?php } ?>
        </div>
       
        </body>
        
        <?php
     }
   } ?>
 <script>
            $(document).ready(function() {
                $('#ativity_log').DataTable();
            } );
        </script>
</html>